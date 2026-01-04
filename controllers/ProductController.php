<?php
namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\Category;
use app\models\Tag;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * ProductController menangani CRUD untuk Produk
 * 
 * Fitur khusus:
 * - Relasi Many-to-One dengan Category (dropdown)
 * - Relasi Many-to-Many dengan Tag (checkbox)
 * - Upload gambar produk
 */
class ProductController extends Controller
{
    /**
     * Access Control - Harus login untuk semua action
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * ACTION INDEX - Menampilkan daftar semua produk
     * 
     * URL: /product/index atau /product
     * 
     * FITUR:
     * - Tampilkan semua produk dalam tabel
     * - Dengan informasi kategori (via relasi)
     * - Dengan tag yang dimiliki (via relasi many-to-many)
     * - SEARCH: by nama produk
     * - FILTER: by kategori, by tag
     * - PAGINATION: 10 produk per halaman
     */
    public function actionIndex($search = null, $category_id = null, $tag_id = null)
    {
        // QUERY BUILDER dengan relasi
        $query = Product::find()->with(['category', 'tags']);
        
        // SEARCH by nama produk
        if ($search) {
            $query->andWhere(['like', 'name', $search]);
        }
        
        // FILTER by kategori
        if ($category_id) {
            $query->andWhere(['category_id' => $category_id]);
        }
        
        // FILTER by tag (via join table product_tag)
        if ($tag_id) {
            $query->joinWith('tags')
                  ->andWhere(['tag.id' => $tag_id]);
        }
        
        // PAGINATION
        $totalCount = $query->count();
        $pageSize = 10; // 10 produk per halaman
        $currentPage = Yii::$app->request->get('page', 1);
        $offset = ($currentPage - 1) * $pageSize;
        
        // Ambil data produk dengan pagination
        $products = $query
            ->orderBy(['id' => SORT_DESC])
            ->offset($offset)
            ->limit($pageSize)
            ->all();
        
        // Hitung total halaman
        $totalPages = ceil($totalCount / $pageSize);
        
        // Ambil data kategori & tag untuk filter dropdown
        $categories = Category::find()->all();
        $tags = Tag::find()->all();
        
        return $this->render('index', [
            'products' => $products,
            'categories' => $categories,
            'tags' => $tags,
            'searchTerm' => $search,
            'selectedCategory' => $category_id,
            'selectedTag' => $tag_id,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * ACTION VIEW - Menampilkan detail 1 produk
     * 
     * URL: /product/view?id=1
     * 
     * FITUR:
     * - Detail lengkap produk
     * - Informasi kategori
     * - Daftar tag yang dimiliki
     * - Gambar produk (jika ada)
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', compact('model'));
    }

    /**
     * ACTION CREATE - Form tambah produk baru
     * 
     * URL: /product/create
     * 
     * FITUR KHUSUS:
     * - Dropdown kategori (relasi Many-to-One)
     * - Checkbox tag (relasi Many-to-Many)
     * - Upload gambar produk
     * 
     * CARA KERJA RELASI MANY-TO-MANY:
     * 1. User pilih beberapa tag via checkbox
     * 2. Data checkbox disimpan di $model->tagIds (array)
     * 3. Saat save(), method afterSave() di Model Product akan:
     *    a. Hapus semua relasi lama di tabel product_tag
     *    b. Insert relasi baru sesuai checkbox yang dipilih
     */
    public function actionCreate()
    {
        $model = new Product();
        
        // Ambil data untuk dropdown dan checkbox
        $categories = Category::find()->all(); // Untuk dropdown kategori
        $tags = Tag::find()->all(); // Untuk checkbox tag
        
        if ($model->load(Yii::$app->request->post())) {
            // Handle upload gambar (opsional)
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                // Generate nama file unik
                $filename = time() . '_' . uniqid() . '.' . $model->image->extension;
                // Simpan ke folder web/uploads/products/
                $uploadPath = Yii::getAlias('@webroot/uploads/products/');
                
                // Buat folder jika belum ada
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Save file
                if ($model->image->saveAs($uploadPath . $filename)) {
                    $model->image = $filename; // Simpan nama file ke database
                } else {
                    $model->image = null;
                }
            } else {
                $model->image = null; // Tidak ada upload
            }
            
            // Save produk (akan trigger afterSave untuk simpan tags)
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Produk berhasil ditambahkan!');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal menambahkan produk. Periksa input Anda.');
            }
        }
        
        // Tampilkan form
        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * ACTION UPDATE - Form edit produk
     * 
     * URL: /product/update?id=1
     * 
     * FITUR KHUSUS:
     * - Tag yang sudah dipilih otomatis ter-check (via afterFind() di Model)
     * - Bisa ganti gambar atau tetap pakai gambar lama
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->image; // Simpan gambar lama
        
        // Ambil data untuk dropdown dan checkbox
        $categories = Category::find()->all();
        $tags = Tag::find()->all();
        
        if ($model->load(Yii::$app->request->post())) {
            // Handle upload gambar baru
            $uploadedImage = UploadedFile::getInstance($model, 'image');
            
            if ($uploadedImage) {
                // Ada upload gambar baru
                $filename = time() . '_' . uniqid() . '.' . $uploadedImage->extension;
                $uploadPath = Yii::getAlias('@webroot/uploads/products/');
                
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                if ($uploadedImage->saveAs($uploadPath . $filename)) {
                    // Hapus gambar lama jika ada
                    if ($oldImage && file_exists($uploadPath . $oldImage)) {
                        unlink($uploadPath . $oldImage);
                    }
                    $model->image = $filename;
                } else {
                    $model->image = $oldImage; // Gagal upload, pakai gambar lama
                }
            } else {
                // Tidak ada upload, pakai gambar lama
                $model->image = $oldImage;
            }
            
            // Save produk
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Produk berhasil diupdate!');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal mengupdate produk.');
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * ACTION DELETE - Hapus produk
     * 
     * URL: /product/delete?id=1
     * 
     * FITUR:
     * - Hapus gambar produk dari folder (jika ada)
     * - Hapus data produk dari database
     * - Hapus relasi di tabel product_tag (otomatis via ON DELETE CASCADE)
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Hapus gambar jika ada
        if ($model->image) {
            $imagePath = Yii::getAlias('@webroot/uploads/products/' . $model->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // Hapus produk
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Produk berhasil dihapus!');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menghapus produk.');
        }
        
        return $this->redirect(['index']);
    }

    /**
     * HELPER METHOD - Cari model berdasarkan ID
     */
    protected function findModel($id)
    {
        $model = Product::findOne($id);
        
        if ($model === null) {
            throw new NotFoundHttpException('Produk tidak ditemukan.');
        }
        
        return $model;
    }
}
