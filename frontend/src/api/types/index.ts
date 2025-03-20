export interface Category {
    id: number;
    name: string;
}

export interface Price {
    amount: number;
    currency_label: string;
    currency_symbol: string;
}

export interface ProductAttribute {
    name: string;
    values: string[];
}

export interface BaseProduct {
    id: string;
    name: string;
    price: number;
    image: string;
    attributes: {
        name: string;
        selectedValue: string;
        values: string[];
    }[];
}

export interface Product {
    id: string;
    name: string;
    description: string;
    category: Category;
    brand: string;
    in_stock: boolean;
    gallery: string[];
    prices: Price[];
    attributes: ProductAttribute[];
    created_at: string;
}

export interface OrderItemAttribute {
    name: string;
    value: string;
}

export interface OrderItemInput {
    productId: string;
    quantity: number;
    attributes: OrderItemAttribute[];
}

export interface GetProductsAndCategoryResponse {
    categories: {
        name: string;
    }[];
    products: Product[];
}

export interface GetProductResponse {
    product: Product;
}

export interface GetCategoriesResponse {
    categories: Category[];
}