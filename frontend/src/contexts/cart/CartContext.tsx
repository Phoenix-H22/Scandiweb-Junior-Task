import {createContext, useContext} from "react";
import {BaseProduct} from '../../api';

export interface CartItem extends BaseProduct {
    quantity: number;
}
export interface CartContextType {
    cart: CartItem[];
    addToCart: (product: BaseProduct) => void;
    removeFromCart: (id: string, attributes: Record<string, string>) => void;
    updateQuantity: (id: string, attributes: Record<string, string>, increment: boolean) => void;
    clearCart: () => void;
    totalItems: number;
    totalPrice: number;
}
export const CartContext = createContext<CartContextType | undefined>(undefined);
export const useCartContext = () => {
    const context = useContext(CartContext);
    if (context === undefined) {
        throw new Error("useCartContext must be used within a CartProvider");
    }
    return context;
};