import { useLocation } from "react-router-dom";
import { useQuery } from "@apollo/client";
import {
    GET_PRODUCTS_AND_CATEGORY,
    GetProductsAndCategoryResponse,
    Product,
    GET_CATEGORIES,
    client,
    Category
} from "../../../../api";
import { ProductCard } from "../ProductCard";
import { useCategory } from "../../../../hooks/useCategory.ts";

interface ProductListProps {
    className?: string;
}

export default function ProductList({ className }: ProductListProps) {
    const { categoryName } = useCategory();
    const location = useLocation();
    const path = location.pathname.slice(1);

    const { data: categoriesData } = useQuery(GET_CATEGORIES);
    const category = categoriesData?.categories.find(
        (cat: Category) => cat.name.toLowerCase() === (path || 'all')
    );
    const categoryId = category?.id;

    const { loading, error, data } = useQuery<GetProductsAndCategoryResponse>(
        GET_PRODUCTS_AND_CATEGORY,
        {
            variables: { categoryId },
            client: client,
        }
    );

    if (loading) {
        return (
            <div className="flex items-center justify-center min-h-[400px]">
                <p className="text-lg text-gray-600">Loading products...</p>
            </div>
        );
    }

    if (error) {
        return (
            <div className="flex items-center justify-center min-h-[400px]">
                <p className="text-lg text-red-600">Error: {error.message}</p>
            </div>
        );
    }

    if (!data?.products?.length) {
        return (
            <div className="flex items-center justify-center min-h-[400px]">
                <p className="text-lg text-gray-600">No products found in this category.</p>
            </div>
        );
    }

    return (
        <section className={`py-20 ${className}`}>
            <div className="container mx-auto">
                <h1 className="text-[42px] font-normal mb-8 uppercase">
                    {categoryName}
                </h1>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                    {data.products.map((product: Product) => (
                        <ProductCard
                            key={product.id}
                            product={product}
                        />
                    ))}
                </div>
            </div>
        </section>
    );
}