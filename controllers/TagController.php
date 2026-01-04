<?php
namespace app\controllers;

use Yii;
use app\models\Tag;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * TagController menangani CRUD untuk Tag
 */
class TagController extends Controller
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
     * ACTION INDEX - Menampilkan daftar semua tag
     * 
     * URL: /tag/index atau /tag
     */
    public function actionIndex()
    {
        // PAGINATION
        $query = Tag::find();
        $totalCount = $query->count();
        $pageSize = 10; // 10 tag per halaman
        $currentPage = Yii::$app->request->get('page', 1);
        $offset = ($currentPage - 1) * $pageSize;
        
        // Ambil data tag dengan pagination
        $tags = $query
            ->orderBy(['id' => SORT_ASC])
            ->offset($offset)
            ->limit($pageSize)
            ->all();
        
        // Hitung total halaman
        $totalPages = ceil($totalCount / $pageSize);
        
        return $this->render('index', [
            'tags' => $tags,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * ACTION VIEW - Menampilkan detail tag
     * 
     * URL: /tag/view?id=1
     * 
     * FITUR:
     * - Detail tag
     * - Daftar produk yang menggunakan tag ini (relasi many-to-many)
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // Ambil semua produk dengan tag ini
        $products = $model->products;
        
        return $this->render('view', [
            'model' => $model,
            'products' => $products,
        ]);
    }

    /**
     * ACTION CREATE - Form tambah tag baru
     * 
     * URL: /tag/create
     * 
     * FITUR:
     * - Input nama tag
     * - Input warna tag (hex color code)
     */
    public function actionCreate()
    {
        $model = new Tag();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Tag berhasil ditambahkan!');
            return $this->redirect(['index']);
        }
        
        return $this->render('create', compact('model'));
    }

    /**
     * ACTION UPDATE - Form edit tag
     * 
     * URL: /tag/update?id=1
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Tag berhasil diupdate!');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        return $this->render('update', compact('model'));
    }

    /**
     * ACTION DELETE - Hapus tag
     * 
     * URL: /tag/delete?id=1
     * 
     * CATATAN:
     * - Relasi di tabel product_tag akan terhapus otomatis (ON DELETE CASCADE)
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Tag berhasil dihapus!');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menghapus tag.');
        }
        
        return $this->redirect(['index']);
    }

    /**
     * HELPER METHOD - Cari model berdasarkan ID
     */
    protected function findModel($id)
    {
        $model = Tag::findOne($id);
        
        if ($model === null) {
            throw new NotFoundHttpException('Tag tidak ditemukan.');
        }
        
        return $model;
    }
}
