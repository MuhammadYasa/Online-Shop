<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\CustomerLoginForm;
use app\models\Customer;
use app\models\ContactForm;
use app\models\Product;
use app\models\Category;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage - Landing page e-commerce
     *
     * @return string
     */
    public function actionIndex($category = null, $search = null)
    {
        // Query produk
        $query = Product::find()->with('category');
        
        // Filter by kategori
        if ($category) {
            $query->where(['category_id' => $category]);
        }
        
        // Search by nama
        if ($search) {
            $query->andWhere(['like', 'name', $search]);
        }
        
        // Pagination
        $totalCount = $query->count();
        $pageSize = 12; // 12 produk per halaman
        $currentPage = Yii::$app->request->get('page', 1);
        $offset = ($currentPage - 1) * $pageSize;
        
        $products = $query
            ->orderBy(['id' => SORT_DESC])
            ->offset($offset)
            ->limit($pageSize)
            ->all();
        
        $totalPages = ceil($totalCount / $pageSize);
        
        // Get all categories untuk filter
        $categories = Category::find()->all();
        
        // Use different layout for shop
        $this->layout = 'shop';
        
        return $this->render('index', [
            'products' => $products,
            'categories' => $categories,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'selectedCategory' => $category,
            'searchTerm' => $search,
        ]);
    }
    
    /**
     * View detail produk
     */
    public function actionProduct($id)
    {
        $product = Product::findOne($id);
        
        if (!$product) {
            throw new \yii\web\NotFoundHttpException('Produk tidak ditemukan.');
        }
        
        $this->layout = 'shop';
        
        return $this->render('product', [
            'product' => $product,
        ]);
    }
    
    /**
     * Customer Registration
     */
    public function actionRegister()
    {
        $model = new Customer();
        $model->scenario = 'register';
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Registrasi berhasil! Silakan login.');
            return $this->redirect(['site/customer-login']);
        }
        
        $this->layout = 'shop';
        
        return $this->render('register', [
            'model' => $model,
        ]);
    }
    
    /**
     * Customer Login
     */
    public function actionCustomerLogin()
    {
        // Cek apakah customer sudah login (via session, bukan Yii::$app->user)
        if (Yii::$app->session->get('customer_id')) {
            return $this->redirect(['site/index']);
        }

        $model = new CustomerLoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Redirect ke halaman yang dituju (returnUrl) atau ke home
            $returnUrl = Yii::$app->session->get('returnUrl');
            if ($returnUrl) {
                Yii::$app->session->remove('returnUrl');
                return $this->redirect($returnUrl);
            }
            return $this->redirect(['site/index']);
        }

        $model->password = '';
        $this->layout = 'shop';
        
        return $this->render('customer-login', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Customer Logout
     */
    public function actionCustomerLogout()
    {
        Yii::$app->session->remove('customer_id');
        Yii::$app->session->remove('customer_name');
        Yii::$app->session->remove('customer_email');
        Yii::$app->session->setFlash('success', 'Anda telah logout.');
        return $this->redirect(['index']);
    }

    /**
     * Customer Profile - Edit Profile
     */
    public function actionProfile()
    {
        $customerId = Yii::$app->session->get('customer_id');
        
        if (!$customerId) {
            Yii::$app->session->setFlash('error', 'Silakan login terlebih dahulu.');
            return $this->redirect(['customer-login']);
        }
        
        $model = Customer::findOne($customerId);
        
        if (!$model) {
            Yii::$app->session->setFlash('error', 'Customer tidak ditemukan.');
            return $this->redirect(['index']);
        }
        
        // Set scenario untuk update profile (tanpa password)
        $model->scenario = 'update';
        
        if ($model->load(Yii::$app->request->post())) {
            // Jika password diisi, hash password baru
            if (!empty($model->password)) {
                $model->setPassword($model->password);
            } else {
                // Jika password kosong, hapus dari attributes agar tidak di-update
                unset($model->password);
            }
            
            if ($model->save()) {
                // Update session dengan data terbaru
                Yii::$app->session->set('customer_name', $model->name);
                Yii::$app->session->set('customer_email', $model->email);
                
                Yii::$app->session->setFlash('success', 'Profile berhasil diupdate.');
                return $this->refresh();
            }
        }
        
        // Clear password untuk keamanan
        $model->password = '';
        
        $this->layout = 'shop';
        
        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
