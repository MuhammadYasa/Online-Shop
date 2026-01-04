<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Order;
use app\models\OrderItem;
use yii\data\ActiveDataProvider;

/**
 * OrderController - Mengelola Pesanan (Admin)
 */
class OrderController extends Controller
{
    /**
     * Access Control - Hanya untuk admin yang sudah login
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Hanya user yang sudah login (admin)
                    ],
                ],
            ],
        ];
    }

    /**
     * Daftar Semua Pesanan
     */
    public function actionIndex()
    {
        $this->view->title = 'Kelola Pesanan';

        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        // Filter berdasarkan status
        $status = Yii::$app->request->get('status');
        if ($status) {
            $dataProvider->query->andWhere(['order_status' => $status]);
        }

        // Filter berdasarkan payment status
        $paymentStatus = Yii::$app->request->get('payment_status');
        if ($paymentStatus) {
            $dataProvider->query->andWhere(['payment_status' => $paymentStatus]);
        }

        // Search
        $search = Yii::$app->request->get('search');
        if ($search) {
            $dataProvider->query->andWhere([
                'or',
                ['like', 'order_number', $search],
                ['like', 'customer_name', $search],
                ['like', 'customer_email', $search],
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'status' => $status,
            'paymentStatus' => $paymentStatus,
            'search' => $search,
        ]);
    }

    /**
     * Detail Pesanan
     */
    public function actionView($id)
    {
        $order = Order::findOne($id);
        
        if (!$order) {
            throw new \yii\web\NotFoundHttpException('Pesanan tidak ditemukan.');
        }

        return $this->render('view', [
            'order' => $order,
        ]);
    }

    /**
     * Update Status Pesanan
     */
    public function actionUpdateStatus($id)
    {
        $order = Order::findOne($id);
        
        if (!$order) {
            throw new \yii\web\NotFoundHttpException('Pesanan tidak ditemukan.');
        }

        if (Yii::$app->request->isPost) {
            $oldOrderStatus = $order->order_status;
            $oldPaymentStatus = $order->payment_status;
            
            $order->order_status = Yii::$app->request->post('order_status');
            $order->payment_status = Yii::$app->request->post('payment_status');
            
            // Add to history
            $order->addStatusHistory($oldOrderStatus, $order->order_status, $oldPaymentStatus, $order->payment_status);
            
            if ($order->save(false)) {
                Yii::$app->session->setFlash('success', 'Status pesanan berhasil diupdate.');
            } else {
                Yii::$app->session->setFlash('error', 'Gagal mengupdate status pesanan.');
            }
            
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('update-status', [
            'order' => $order,
        ]);
    }

    /**
     * Statistik Pesanan
     */
    public function actionStats()
    {
        $totalOrders = Order::find()->count();
        $pendingOrders = Order::find()->where(['order_status' => 'pending'])->count();
        $processingOrders = Order::find()->where(['order_status' => 'processing'])->count();
        $shippedOrders = Order::find()->where(['order_status' => 'shipped'])->count();
        $deliveredOrders = Order::find()->where(['order_status' => 'delivered'])->count();
        $cancelledOrders = Order::find()->where(['order_status' => 'cancelled'])->count();
        
        $totalRevenue = Order::find()->where(['payment_status' => 'paid'])->sum('total') ?? 0;
        $pendingPayment = Order::find()->where(['payment_status' => 'pending'])->sum('total') ?? 0;

        return $this->render('stats', [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'processingOrders' => $processingOrders,
            'shippedOrders' => $shippedOrders,
            'deliveredOrders' => $deliveredOrders,
            'cancelledOrders' => $cancelledOrders,
            'totalRevenue' => $totalRevenue,
            'pendingPayment' => $pendingPayment,
        ]);
    }

    /**
     * Hapus Pesanan
     */
    public function actionDelete($id)
    {
        $order = Order::findOne($id);
        
        if (!$order) {
            throw new \yii\web\NotFoundHttpException('Pesanan tidak ditemukan.');
        }

        // Hapus order items dulu
        OrderItem::deleteAll(['order_id' => $id]);
        
        // Hapus order
        if ($order->delete()) {
            Yii::$app->session->setFlash('success', 'Pesanan berhasil dihapus.');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menghapus pesanan.');
        }

        return $this->redirect(['index']);
    }
}
