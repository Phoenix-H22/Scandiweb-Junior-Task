import {Link, useLocation, useNavigate} from "react-router-dom";
import {cn} from "../../../lib/utils.ts";
import {Category, GET_CATEGORIES,useQuery,client} from "../../../api";
import {CartOverlay} from "../../features/cart/CartOverlay";
import {useEffect} from "react";
import {useCategory} from "../../../hooks/useCategory.ts";
import {useCart} from "../../../hooks/useCart.ts";
import {useCartOverlay} from "../../../contexts/cart/useCartOverlay.tsx";

function Navbar() {
    const { isCartOpen, setIsCartOpen } = useCartOverlay();
    const { totalItems } = useCart();
    const { setCategoryName } = useCategory();
    const navigate = useNavigate();
    const location = useLocation();
    const currentPath = location.pathname;

    const { loading, error, data } = useQuery<{ categories: Category[] }>(GET_CATEGORIES, {
        client: client,
    });

    useEffect(() => {
        if (!data?.categories) return;

        const setInitialCategory = () => {
            const path = currentPath === '/' ? 'all' : currentPath.slice(1);
            const category = data.categories.find(
                (cat) => cat.name.toLowerCase() === path.toLowerCase()
            );

            if (category) {
                setCategoryName(category.name);
            }
        };

        setInitialCategory();
    }, [data, currentPath, setCategoryName]);

    if (loading) return (
        <header className="relative h-[80px] flex items-center justify-between mt-10 sm:mt-0">
            <nav>
                <ul className="flex items-center">
                    <li className="relative text-[18px] uppercase px-2 sm:px-4">
                        <Link to="/all" data-testid="category-link">ALL</Link>
                    </li>
                </ul>
            </nav>
        </header>
    );
    if (error) return <p>Error: {error.message}</p>;

    const handleCategoryClick = (category: Category) => {
        setCategoryName(category.name);
        const path = category.name.toLowerCase();
        navigate(`/${path}`);
    };

    const isActiveCategory = (category: Category) => {
        const categoryPath = `/${category.name.toLowerCase()}`;
        return currentPath === categoryPath ||
            (categoryPath === '/all' && currentPath === '/');
    };

    return (
        <header className="relative h-[80px] flex items-center justify-between mt-10 sm:mt-0">
            <nav>
                <ul className="flex items-center">
                    {data?.categories.map((category: Category) => {
                        const isActive = isActiveCategory(category);
                        const categoryPath = `/${category.name.toLowerCase()}`;
                        return (
                            <li
                                key={category.id}
                                className={cn(
                                    "relative text-[18px] uppercase px-2 sm:px-4",
                                    isActive && "text-[#5ECE7B] font-semibold"
                                )}
                            >
                                <Link
                                    to={categoryPath}
                                    className="cursor-pointer"
                                    onClick={() => handleCategoryClick(category)}
                                    data-testid={isActive ? 'active-category-link' : 'category-link'}
                                >
                                    {category.name}
                                </Link>
                                {isActive && (
                                    <div className="absolute left-0 -bottom-6 w-full h-0.5 bg-[#5ECE7B]" />
                                )}
                            </li>
                        );
                    })}
                </ul>
            </nav>

            <Link to="/">
                <img
                    src="/icons/ecommerce-logo.svg"
                    alt="ecommerce-logo"
                    className="absolute -top-6 sm:top-1/2 left-1/2 -translate-x-1/2 sm:-translate-y-1/2"
                />
            </Link>

            <div className="relative">
                <button
                    className="relative cursor-pointer mr-3"
                    onClick={(e) => {
                        e.stopPropagation();
                        setIsCartOpen(!isCartOpen);
                    }}
                    data-testid="cart-btn"
                >
                    <img src="/icons/cart.svg" alt="cart" />
                    {totalItems > 0 && (
                        <div className="absolute -top-2 -right-3 flex items-center justify-center px-1.5 h-[20px] rounded-full bg-[#1D1F22] text-white font-semibold text-center roboto">
                            <span>{totalItems}</span>
                        </div>
                    )}
                </button>
                <CartOverlay
                    open={isCartOpen}
                    onClose={() => setIsCartOpen(false)}
                />
            </div>
        </header>
    );
}

export default Navbar;