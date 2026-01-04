<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Category;
use app\models\Product;
use app\models\Tag;

/**
 * DashboardController menampilkan halaman dashboard dengan statistik
 */
class DashboardController extends Controller
{
    /**
     * Access Control - Harus login untuk akses dashboard
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
     * Halaman Dashboard Utama
     * 
     * Menampilkan:
     * - Total kategori, produk, tag
     * - Produk terbaru
     * - Kategori dengan jumlah produk terbanyak
     * - Tag paling populer
     */
    public function actionIndex()
    {
        // STATISTIK DASAR
        $totalCategories = Category::find()->count();
        $totalProducts = Product::find()->count();
        $totalTags = Tag::find()->count();
        
        // Total stok semua produk
        $totalStock = Product::find()->sum('stock') ?? 0;
        
        // PRODUK TERBARU (5 produk terakhir)
        $recentProducts = Product::find()
            ->with('category')  // Eager loading relasi
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();
        
        // KATEGORI DENGAN PRODUK TERBANYAK
        $topCategories = Category::find()
            ->select(['category.*', 'COUNT(product.id) as product_count'])
            ->leftJoin('product', 'product.category_id = category.id')
            ->groupBy(['category.id'])
            ->orderBy(['product_count' => SORT_DESC])
            ->limit(5)
            ->asArray()
            ->all();
        
        // TAG PALING POPULER (paling banyak digunakan)
        $popularTags = Tag::find()
            ->select(['tag.*', 'COUNT(product_tag.product_id) as usage_count'])
            ->leftJoin('product_tag', 'product_tag.tag_id = tag.id')
            ->groupBy(['tag.id'])
            ->orderBy(['usage_count' => SORT_DESC])
            ->limit(5)
            ->asArray()
            ->all();
        
        // PRODUK STOK RENDAH (warning jika stok < 10)
        $lowStockProducts = Product::find()
            ->with('category')
            ->where(['<', 'stock', 10])
            ->orderBy(['stock' => SORT_ASC])
            ->limit(5)
            ->all();
        
        return $this->render('index', [
            'totalCategories' => $totalCategories,
            'totalProducts' => $totalProducts,
            'totalTags' => $totalTags,
            'totalStock' => $totalStock,
            'recentProducts' => $recentProducts,
            'topCategories' => $topCategories,
            'popularTags' => $popularTags,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}
