<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Order;
use app\models\OrderItem;
use app\models\Cart;
use app\models\Customer;
use yii\web\UploadedFile;

/**
 * CheckoutController - Checkout & Payment
 */
class CheckoutController extends Controller
{
    public $layout = 'shop';
    
    /**
     * Checkout Page
     */
    public function actionIndex()
    {
        // VALIDASI: Customer harus login untuk checkout
        $customerId = Yii::$app->session->get('customer_id');
        
        if (!$customerId) {
            // Simpan URL untuk redirect setelah login
            Yii::$app->session->set('returnUrl', Yii::$app->request->url);
            // Langsung redirect ke login tanpa flash message
            return $this->redirect(['/site/customer-login']);
        }
        
        $sessionId = Yii::$app->session->getId();
        
        $items = Cart::getItems($sessionId, $customerId);
        $subtotal = Cart::getTotal($sessionId, $customerId);
        
        if (empty($items)) {
            Yii::$app->session->setFlash('error', 'Keranjang belanja kosong.');
            return $this->redirect(['cart/index']);
        }
        
        $model = new Order();
        
        // Pre-fill data jika customer sudah login
        if ($customerId) {
            $customer = Customer::findOne($customerId);
            if ($customer) {
                $model->customer_id = $customer->id;
                $model->customer_name = $customer->name;
                $model->customer_email = $customer->email;
                $model->customer_phone = $customer->phone;
                $model->shipping_address = $customer->address;
            }
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $model->subtotal = $subtotal;
            $model->shipping_cost = 10000; // Flat shipping cost
            $model->total = $model->subtotal + $model->shipping_cost;
            
            // Handle upload bukti transfer
            if ($model->payment_method == 'transfer') {
                $file = UploadedFile::getInstance($model, 'payment_proof');
                if ($file) {
                    $filename = 'payment_' . time() . '.' . $file->extension;
                    $uploadPath = Yii::getAlias('@webroot/uploads/payments/');
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    if ($file->saveAs($uploadPath . $filename)) {
                        $model->payment_proof = $filename;
                    }
                }
            }
            
            // Save order
            if ($model->save()) {
                // Save order items
                foreach ($items as $item) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $model->id;
                    $orderItem->product_id = $item->product_id;
                    $orderItem->product_name = $item->product->name;
                    $orderItem->product_price = $item->product->price;
                    $orderItem->quantity = $item->quantity;
                    $orderItem->subtotal = $item->getSubtotal();
                    $orderItem->save();
                    
                    // Update stock
                    $product = $item->product;
                    $product->stock -= $item->quantity;
                    $product->save(false);
                }
                
                // Clear cart
                Cart::clearCart($sessionId, $customerId);
                
                Yii::$app->session->setFlash('success', 'Pesanan berhasil dibuat! Nomor pesanan: ' . $model->order_number);
                return $this->redirect(['success', 'id' => $model->id]);
            }
        }
        
        return $this->render('index', [
            'order' => $model,
            'cartItems' => $items,
            'total' => $model->load(Yii::$app->request->post()) ? ($subtotal + 10000) : $subtotal,
        ]);
    }
    
    /**
     * Success Page
     */
    public function actionSuccess($id)
    {
        // VALIDASI: Customer harus login
        $customerId = Yii::$app->session->get('customer_id');
        if (!$customerId) {
            Yii::$app->session->setFlash('error', 'Silakan login untuk melihat pesanan.');
            return $this->redirect(['/site/customer-login']);
        }
        
        $order = Order::findOne($id);
        
        if (!$order) {
            throw new \yii\web\NotFoundHttpException('Pesanan tidak ditemukan.');
        }
        
        // Verify ownership
        if ($order->customer_id != $customerId) {
            throw new \yii\web\ForbiddenHttpException('Anda tidak memiliki akses ke pesanan ini.');
        }
        
        return $this->render('success', [
            'order' => $order,
        ]);
    }
    
    /**
     * My Orders (Customer)
     */
    public function actionOrders()
    {
        $customerId = Yii::$app->session->get('customer_id');
        if (!$customerId) {
            Yii::$app->session->setFlash('error', 'Silakan login terlebih dahulu.');
            return $this->redirect(['/site/customer-login']);
        }
        
        $orders = Order::find()
            ->where(['customer_id' => $customerId])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        return $this->render('orders', [
            'orders' => $orders,
        ]);
    }
    
    /**
     * Order Detail
     */
    public function actionView($id)
    {
        // VALIDASI: Customer harus login
        $customerId = Yii::$app->session->get('customer_id');
        if (!$customerId) {
            Yii::$app->session->setFlash('error', 'Silakan login untuk melihat detail pesanan.');
            return $this->redirect(['/site/customer-login']);
        }
        
        $order = Order::findOne($id);
        
        if (!$order) {
            throw new \yii\web\NotFoundHttpException('Pesanan tidak ditemukan.');
        }
        
        // Verify ownership
        if ($order->customer_id != $customerId) {
            throw new \yii\web\ForbiddenHttpException('Anda tidak memiliki akses ke pesanan ini.');
        }
        
        return $this->render('view', [
            'order' => $order,
        ]);
    }
}
