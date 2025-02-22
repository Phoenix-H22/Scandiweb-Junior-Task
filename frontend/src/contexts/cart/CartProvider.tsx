import React, {ReactNode, useEffect, useState} from "react";
import {BaseProduct} from "../../api";
import {CartContext, CartItem} from "./CartContext.tsx";
interface CartProviderProps {
    children: ReactNode;
}
export const CartProvider: React.FC<CartProviderProps> = ({ children }) => {
    const [cart, setCart] = useState<CartItem[]>(() => {
        const storedCart = localStorage.getItem("cart");
        return storedCart ? JSON.parse(storedCart) : [];
    });

    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    const generateCartItemId = (id: string, attributes: Record<string, string>) => {
        const sortedAttributes = Object.entries(attributes)
            .sort(([a], [b]) => a.localeCompare(b))
            .map(([key, value]) => `${key}:${value}`)
            .join('|');
        return `${id}-${sortedAttributes}`;
    };

    const addToCart = (product: BaseProduct) => {
        setCart(prevCart => {
            const cartItemId = generateCartItemId(product.id,
                Object.fromEntries(product.attributes.map(attr => [attr.name, attr.selectedValue])));

            const existingItemIndex = prevCart.findIndex(item =>
                generateCartItemId(item.id,
                    Object.fromEntries(item.attributes.map(attr => [attr.name, attr.selectedValue]))) === cartItemId);

            if (existingItemIndex > -1) {
                return prevCart.map((item, index) =>
                    index === existingItemIndex
                        ? { ...item, quantity: item.quantity + 1 }
                        : item
                );
            }

            return [...prevCart, { ...product, quantity: 1 }];
        });
    };

    const updateQuantity = (id: string, attributes: Record<string, string>, increment: boolean) => {
        setCart(prevCart => {
            const cartItemId = generateCartItemId(id, attributes);

            return prevCart.reduce((acc, item) => {
                const itemId = generateCartItemId(item.id,
                    Object.fromEntries(item.attributes.map(attr => [attr.name, attr.selectedValue])));

                if (itemId === cartItemId) {
                    const newQuantity = increment ? item.quantity + 1 : item.quantity - 1;
                    if (newQuantity === 0) return acc;
                    return [...acc, { ...item, quantity: newQuantity }];
                }
                return [...acc, item];
            }, [] as CartItem[]);
        });
    };

    const removeFromCart = (id: string, attributes: Record<string, string>) => {
        setCart(prevCart => {
            const cartItemId = generateCartItemId(id, attributes);
            return prevCart.filter(item =>
                generateCartItemId(item.id,
                    Object.fromEntries(item.attributes.map(attr => [attr.name, attr.selectedValue]))) !== cartItemId);
        });
    };

    const clearCart = () => {
        setCart([]);
    };

    useEffect(() => {
        localStorage.setItem("cart", JSON.stringify(cart));
    }, [cart]);

    return (
        <CartContext.Provider value={{
            cart,
            addToCart,
            removeFromCart,
            updateQuantity,
            clearCart,
            totalItems,
            totalPrice
        }}>
            {children}
        </CartContext.Provider>
    );
};
