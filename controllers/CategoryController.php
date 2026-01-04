<?php
namespace app\controllers;

use Yii;
use app\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * CategoryController menangani CRUD untuk Kategori
 */
class CategoryController extends Controller
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
                        'roles' => ['@'], // @ = user yang sudah login
                    ],
                ],
            ],
        ];
    }

    /**
     * ACTION INDEX - Menampilkan daftar semua kategori
     * 
     * URL: /category/index atau /category
     * Method: GET
     * 
     * CARA KERJA:
     * 1. Ambil semua data category dari database
     * 2. Kirim data ke view 'index.php'
     * 3. View akan menampilkan dalam bentuk tabel
     */
    public function actionIndex()
    {
        // PAGINATION
        $query = Category::find();
        $totalCount = $query->count();
        $pageSize = 10; // 10 kategori per halaman
        $currentPage = Yii::$app->request->get('page', 1);
        $offset = ($currentPage - 1) * $pageSize;
        
        // Ambil data kategori dengan pagination
        $categories = $query
            ->orderBy(['id' => SORT_ASC])
            ->offset($offset)
            ->limit($pageSize)
            ->all();
        
        // Hitung total halaman
        $totalPages = ceil($totalCount / $pageSize);
        
        // render() = tampilkan view 'views/category/index.php'
        return $this->render('index', [
            'categories' => $categories,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * ACTION VIEW - Menampilkan detail 1 kategori
     * 
     * URL: /category/view?id=1
     * Method: GET
     * Parameter: $id (ID kategori yang ingin dilihat)
     * 
     * CARA KERJA:
     * 1. Cari kategori berdasarkan ID
     * 2. Jika tidak ada, tampilkan error 404
     * 3. Jika ada, tampilkan detail kategori + daftar produknya
     */
    public function actionView($id)
    {
        // Cari kategori dengan ID tertentu
        $model = $this->findModel($id);
        
        // Ambil semua produk dalam kategori ini (relasi One-to-Many)
        $products = $model->products; // Gunakan relasi getProducts()
        
        // Tampilkan view detail
        return $this->render('view', [
            'model' => $model,
            'products' => $products,
        ]);
    }

    /**
     * ACTION CREATE - Form untuk tambah kategori baru
     * 
     * URL: /category/create
     * Method: GET (tampilkan form) | POST (simpan data)
     * 
     * CARA KERJA:
     * GET Request:
     *   1. Buat object Category kosong
     *   2. Tampilkan form kosong
     * 
     * POST Request (setelah user klik submit):
     *   1. Ambil data dari form
     *   2. Validasi data (cek rules di Model)
     *   3. Jika valid, simpan ke database
     *   4. Redirect ke halaman index
     *   5. Jika tidak valid, tampilkan form lagi dengan error
     */
    public function actionCreate()
    {
        // Buat object Category baru (kosong)
        $model = new Category();
        
        // Cek apakah ada data POST (user submit form?)
        if ($model->load(Yii::$app->request->post())) {
            // load() = ambil data dari form dan isi ke $model
            // Form field 'Category[name]' → masuk ke $model->name
            
            // save() = validasi + simpan ke database
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Kategori berhasil ditambahkan!');
                return $this->redirect(['index']);
            } else {
                // Jika gagal validasi
                Yii::$app->session->setFlash('error', 'Gagal menambahkan kategori. Periksa input Anda.');
            }
        }
        
        // Tampilkan form (GET request atau POST gagal)
        return $this->render('create', compact('model'));
    }

    /**
     * ACTION UPDATE - Form untuk edit kategori
     * 
     * URL: /category/update?id=1
     * Method: GET (tampilkan form) | POST (simpan perubahan)
     * Parameter: $id (ID kategori yang ingin diedit)
     * 
     * CARA KERJA:
     * GET Request:
     *   1. Cari kategori berdasarkan ID
     *   2. Tampilkan form dengan data kategori yang ada
     * 
     * POST Request:
     *   1. Ambil data dari form
     *   2. Update data kategori
     *   3. Validasi dan simpan
     *   4. Redirect ke halaman index
     */
    public function actionUpdate($id)
    {
        // Cari kategori yang ingin diedit
        $model = $this->findModel($id);
        
        // Cek apakah ada data POST
        if ($model->load(Yii::$app->request->post())) {
            // save() akan update data yang sudah ada
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Kategori berhasil diupdate!');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal mengupdate kategori.');
            }
        }
        
        // Tampilkan form edit dengan data kategori
        return $this->render('update', compact('model'));
    }

    /**
     * ACTION DELETE - Hapus kategori
     * 
     * URL: /category/delete?id=1
     * Method: POST (untuk keamanan, delete harus POST)
     * Parameter: $id (ID kategori yang ingin dihapus)
     * 
     * CARA KERJA:
     * 1. Cari kategori berdasarkan ID
     * 2. Hapus dari database
     * 3. Redirect ke halaman index
     * 
     * CATATAN:
     * - Karena ada ON DELETE CASCADE, semua produk di kategori ini juga terhapus
     */
    public function actionDelete($id)
    {
        // Cari dan hapus kategori
        $model = $this->findModel($id);
        
        // delete() = DELETE FROM category WHERE id = $id
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Kategori berhasil dihapus!');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menghapus kategori.');
        }
        
        return $this->redirect(['index']);
    }

    /**
     * HELPER METHOD - Cari model berdasarkan ID
     * 
     * Fungsi pembantu untuk menghindari duplikasi kode
     * Digunakan di actionView, actionUpdate, actionDelete
     * 
     * @param int $id ID kategori
     * @return Category object kategori
     * @throws NotFoundHttpException jika tidak ditemukan
     */
    protected function findModel($id)
    {
        // findOne($id) = SELECT * FROM category WHERE id = $id LIMIT 1
        $model = Category::findOne($id);
        
        // Jika tidak ada, throw exception (tampilkan halaman 404)
        if ($model === null) {
            throw new NotFoundHttpException('Kategori tidak ditemukan.');
        }
        
        return $model;
    }
}