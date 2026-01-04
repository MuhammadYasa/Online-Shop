<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Cart;
use app\models\Product;

/**
 * CartController - Shopping Cart
 */
class CartController extends Controller
{
    public $layout = 'shop';
    
    /**
     * View Cart
     */
    public function actionIndex()
    {
        $sessionId = Yii::$app->session->getId();
        $customerId = Yii::$app->session->get('customer_id'); // Customer session
        
        $items = Cart::getItems($sessionId, $customerId);
        $total = Cart::getTotal($sessionId, $customerId);
        
        return $this->render('index', [
            'items' => $items,
            'total' => $total,
        ]);
    }
    
    /**
     * Add to Cart
     */
    public function actionAdd($id)
    {
        $product = Product::findOne($id);
        
        if (!$product) {
            Yii::$app->session->setFlash('error', 'Produk tidak ditemukan.');
            return $this->redirect(['site/index']);
        }
        
        $sessionId = Yii::$app->session->getId();
        $customerId = Yii::$app->session->get('customer_id'); // Customer session
        $quantity = Yii::$app->request->post('quantity', 1);
        
        if (Cart::addItem($id, $quantity, $sessionId, $customerId)) {
            Yii::$app->session->setFlash('success', 'Produk ditambahkan ke keranjang!');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menambahkan produk.');
        }
        
        return $this->redirect(['index']);
    }
    
    /**
     * Update Quantity
     */
    public function actionUpdate($id)
    {
        $quantity = Yii::$app->request->post('quantity', 1);
        
        if (Cart::updateQuantity($id, $quantity)) {
            Yii::$app->session->setFlash('success', 'Keranjang diupdate.');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal update keranjang.');
        }
        
        return $this->redirect(['index']);
    }
    
    /**
     * Remove Item
     */
    public function actionRemove($id)
    {
        if (Cart::removeItem($id)) {
            Yii::$app->session->setFlash('success', 'Item dihapus dari keranjang.');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menghapus item.');
        }
        
        return $this->redirect(['index']);
    }
    
    /**
     * Clear Cart
     */
    public function actionClear()
    {
        $sessionId = Yii::$app->session->getId();
        $customerId = Yii::$app->session->get('customer_id'); // Customer session
        
        if (Cart::clearCart($sessionId, $customerId)) {
            Yii::$app->session->setFlash('success', 'Keranjang dikosongkan.');
        }
        
        return $this->redirect(['index']);
    }
}
