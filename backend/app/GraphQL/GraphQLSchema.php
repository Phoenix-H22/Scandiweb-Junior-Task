<?php
namespace App\GraphQL;

use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

class GraphQLSchema {
    private static $productType = null;
    private static $priceType = null;
    private static $categoryType = null;
    private static $orderType = null;
    private static $attributeType = null;

    public static function createSchema() {
        return new Schema([
            'query' => new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'products' => [
                        'type' => Type::listOf(self::productType()),
                        'args' => [
                            'categoryId' => Type::int(),
                        ],
                        'resolve' => function($root, $args) {
                            try {
                                $productModel = new Product();
                                return $productModel->findAllByCategory($args['categoryId'] ?? null);
                            } catch (\Throwable $e) {
                                error_log("GraphQL Error: " . $e->getMessage());
                                throw new \Exception("Internal Server Error: " . $e->getMessage());
                            }
                        }
                    ],
                    'product' => [
                        'type' => self::productType(),
                        'args' => [
                            'id' => Type::nonNull(Type::string())
                        ],
                        'resolve' => function($root, $args) {
                            try {
                                $productModel = new Product();
                                return $productModel->findById($args['id']);
                            } catch (\Throwable $e) {
                                error_log("GraphQL Error: " . $e->getMessage());
                                throw $e;
                            }
                        }
                    ],
                    // ✅ Fetch all categories
                    'categories' => [
                        'type' => Type::listOf(self::categoryType()),
                        'args' => [
                            'id' => Type::int(),
                        ],
                        'resolve' => function($root, $args) {
                            try {
                                $categoryModel = new Category();
                                return isset($args['id'])
                                    ? $categoryModel->findById($args['id'])
                                    : $categoryModel->findAll();
                            } catch (\Throwable $e) {
                                error_log("GraphQL Error: " . $e->getMessage());
                                throw new \Exception("Internal Server Error: " . $e->getMessage());
                            }
                        }
                    ],


                    'orders' => [
                        'type' => Type::listOf(self::orderType()),
                        'resolve' => function() {
                            $orderModel = new Order();
                            return $orderModel->findAll();
                        }
                    ]
            ]
            ]),
            'mutation' => self::mutationType()
        ]);
    }

    private static function productType() {
        if (self::$productType === null) {
            self::$productType = new ObjectType([
                'name' => 'Product',
                'fields' => function() {
                    return [
                        'id' => Type::string(),
                        'name' => Type::string(),
                        'category' => self::categoryType(),
                        'description' => Type::string(),
                        'brand' => Type::string(),
                        'in_stock' => Type::boolean(),
                        'prices' => Type::listOf(self::priceType()),
                        'attributes' => Type::listOf(self::attributeType()),
                        'gallery' => Type::listOf(Type::string()),
                        'created_at' => Type::string(),
                    ];
                }
            ]);
        }
        return self::$productType;
    }

    private static function attributeType() {
        if (self::$attributeType === null) {
            self::$attributeType = new ObjectType([
                'name' => 'Attribute',
                'fields' => [
                    'name' => Type::string(),
                    'values' => Type::listOf(Type::string())
                ]
            ]);
        }
        return self::$attributeType;
    }
    private static function categoryType() {
        if (self::$categoryType === null) {
            self::$categoryType = new ObjectType([
                'name' => 'Category',
                'fields' => [
                    'id' => Type::int(),
                    'name' => Type::string()
                ]
            ]);
        }
        return self::$categoryType;
    }

    private static function priceType() {
        if (self::$priceType === null) {
            self::$priceType = new ObjectType([
                'name' => 'Price',
                'fields' => [
                    'amount' => Type::float(),
                    'currency_label' => Type::string(),
                    'currency_symbol' => Type::string()
                ]
            ]);
        }
        return self::$priceType;
    }


    private static function orderType() {
        if (self::$orderType === null) {
            self::$orderType = new ObjectType([
                'name' => 'Order',
                'fields' => function() {
                    return [
                        'id' => Type::int(),
                        'total_amount' => Type::float(),
                        'currency' => Type::string(),
                        'products' => [
                            'type' => Type::listOf(self::productType()),
                            'resolve' => function($order) {
                                return (new Order())->getOrderProductDetails($order['id']);
                            }
                        ]
                    ];
                }
            ]);
        }
        return self::$orderType;
    }


    private static function mutationType() {
        return new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => [
                    'type' => Type::string(),
                    'args' => [
                        'items' => Type::nonNull(Type::listOf(new InputObjectType([
                            'name' => 'OrderItemInput',
                            'fields' => [
                                'productId' => Type::nonNull(Type::string()),
                                'quantity' => Type::nonNull(Type::int()),
                                'attributes' => Type::listOf(new InputObjectType([
                                    'name' => 'OrderItemAttributeInput',
                                    'fields' => [
                                        'name' => Type::nonNull(Type::string()),
                                        'value' => Type::nonNull(Type::string())
                                    ]
                                ]))
                            ]
                        ]))),
                        'total' => Type::nonNull(Type::float()),
                        'currency' => Type::nonNull(Type::string())
                    ],
                    'resolve' => function ($root, $args) {
                        $order = new Order();
                        return $order->createOrder($args);
                    }
                ]
            ]
        ]);
    }
}