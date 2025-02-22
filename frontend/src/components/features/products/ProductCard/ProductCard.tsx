import { Link } from "react-router-dom";
import { Product } from "../../../../api";
import { useCart } from "../../../../hooks/useCart.ts";
import {formatPrice} from "../../../../utils/formatters/price.ts";
import {useCartOverlay} from "../../../../contexts/cart/useCartOverlay.tsx";

interface ProductCardProps {
    product: Product;
}

export default function ProductCard({ product }: ProductCardProps) {
    const { addToCart } = useCart();
    const { setIsCartOpen } = useCartOverlay();
    const price = formatPrice(product.prices[0].amount, product.prices[0].currency_label);

    const getKebabCase = (str: string) => {
        return str
            .toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^a-z0-9-]/g, '');
    };

    const handleQuickAdd = (e: React.MouseEvent) => {
        e.preventDefault();

        const cartItem = {
            id: product.id,
            name: product.name,
            price: product.prices[0].amount,
            image: product.gallery[0],
            attributes: product.attributes.map(attr => ({
                name: attr.name,
                selectedValue: attr.values[0],
                values: attr.values
            }))
        };

        addToCart(cartItem);
        setIsCartOpen(true);
    };

    return (
        <Link
            to={`/product-details/${product.id}`}
            className="group p-4 relative flex flex-col duration-150 hover:scale-105 hover:shadow-xl"
            data-testid={`product-${getKebabCase(product.name)}`}
        >
            <div className="relative">
                <div className="relative aspect-square w-full overflow-hidden rounded-sm bg-gray-100">
                    <img
                        src={product.gallery[0]}
                        alt={product.name}
                        className={`object-cover w-full h-full transition-opacity duration-300 ${
                            !product.in_stock ? 'opacity-50' : ''
                        }`}
                    />
                    {!product.in_stock && (
                        <div className="absolute inset-0 flex items-center justify-center bg-white/80">
              <span className="text-gray-400 text-2xl font-medium">
                OUT OF STOCK
              </span>
                        </div>
                    )}
                </div>
                {product.in_stock && (
                    <button
                        onClick={handleQuickAdd}
                        className="absolute z-30 -bottom-5 right-5 w-10 h-10 rounded-full flex items-center justify-center duration-200 bg-[#5ECE7B] opacity-0 group-hover:opacity-100 cursor-pointer hover:bg-[#4eb369] transform hover:scale-110 transition-all"
                    >
                        <img src="/icons/white-cart.svg" alt="add-to-cart" />
                    </button>
                )}
            </div>
            <div className="mt-4 flex flex-col gap-1">
                <h3 className="text-2xl font-light text-gray-700 line-clamp-1">
                    {product.name}
                </h3>
                <p className="text-xl font-semibold text-[#1D1F22]">
                    {price}
                </p>
            </div>
        </Link>
    );
}