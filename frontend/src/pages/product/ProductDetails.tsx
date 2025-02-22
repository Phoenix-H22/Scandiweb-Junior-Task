import { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import { useQuery } from "@apollo/client";
import { BaseProduct,GET_PRODUCT,client,GetProductResponse  } from "../../api";
import { useCartContext } from "../../contexts/cart/CartContext.tsx";
import {
    ProductGalleryThumbnails,
    ProductMainImage,
    ProductHeader,
    ProductAttributes,
    ProductPrice,
    AddToCartButton,
    ProductDescription
} from './components';
import { useCartOverlay } from "../../contexts/cart/useCartOverlay";

export default function ProductDetails() {
    const { id } = useParams<{ id: string }>();
    const [currentImageIndex, setCurrentImageIndex] = useState(0);
    const [selectedAttributes, setSelectedAttributes] = useState<Record<string, string>>({});
    const { addToCart } = useCartContext();
    const { setIsCartOpen } = useCartOverlay();


    const { loading, error, data } = useQuery<GetProductResponse>(GET_PRODUCT, {
        client,
        variables: { id },
        skip: !id,
    });
    useEffect(() => {
        if (data?.product) {
            setSelectedAttributes({});
        }
    }, [data]);

    if (loading) {
        return (
            <div className="min-h-screen flex items-center justify-center">
                <p className="text-lg text-gray-600">Loading product details...</p>
            </div>
        );
    }

    if (error) {
        return (
            <div className="min-h-screen flex items-center justify-center">
                <p className="text-lg text-red-600">Error: {error.message}</p>
            </div>
        );
    }

    if (!data?.product) {
        return (
            <div className="min-h-screen flex items-center justify-center">
                <p className="text-lg text-gray-600">Product not found</p>
            </div>
        );
    }

    const { product } = data;

    const handlePrevImage = () => {
        setCurrentImageIndex((prev) => (prev === 0 ? product.gallery.length - 1 : prev - 1));
    };

    const handleNextImage = () => {
        setCurrentImageIndex((prev) => (prev === product.gallery.length - 1 ? 0 : prev + 1));
    };

    const handleAttributeSelect = (attributeName: string, value: string) => {
        setSelectedAttributes((prev) => ({
            ...prev,
            [attributeName]: value,
        }));
    };

    const handleAddToCart = () => {
        if (Object.keys(selectedAttributes).length !== product.attributes.length) {
            alert("Please select all options");
            return;
        }

        const cartProduct: BaseProduct = {
            id: String(product.id),
            name: product.name,
            price: product.prices[0].amount,
            image: product.gallery[0],
            attributes: product.attributes.map(attr => ({
                name: attr.name,
                selectedValue: selectedAttributes[attr.name],
                values: attr.values
            }))
        };

        addToCart(cartProduct);
        setIsCartOpen(true);
    };

    return (
        <div className="flex items-center py-4 sm:py-10 mt-[60px] sm:mt-[120px]">
            <div className="container mx-auto px-4 sm:px-8">
                <div className="flex flex-col sm:flex-row gap-10">
                    {/* Product Images */}
                    <div className="sm:w-3/4 relative flex flex-col">
                        <div className="flex gap-6 items-start" data-testid="product-gallery">
                            {/* Thumbnails */}
                            <ProductGalleryThumbnails
                                gallery={product.gallery}
                                currentIndex={currentImageIndex}
                                onSelect={setCurrentImageIndex}
                            />

                            {/* Main Image */}
                            <ProductMainImage
                                image={product.gallery[currentImageIndex]}
                                name={product.name}
                                hasMultipleImages={product.gallery.length > 1}
                                onPrev={handlePrevImage}
                                onNext={handleNextImage}
                            />
                        </div>
                    </div>

                    {/* Product Info */}
                    <div className="sm:w-1/4 space-y-6">
                        <ProductHeader name={product.name} brand={product.brand} />

                        {/* Attributes */}
                        <ProductAttributes
                            attributes={product.attributes}
                            selectedAttributes={selectedAttributes}
                            onSelect={handleAttributeSelect}
                        />

                        <ProductPrice
                            price={product.prices[0]}
                        />

                        <AddToCartButton
                            inStock={product.in_stock}
                            isValid={Object.keys(selectedAttributes).length === product.attributes.length}
                            onClick={handleAddToCart}
                        />

                        <ProductDescription
                            description={product.description}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}