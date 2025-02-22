import { useContext } from 'react';
import { CartContext } from '../contexts/cart/CartContext.tsx';
export { CartProvider } from '../contexts/cart/CartProvider.tsx';

export const useCart = () => {
    const context = useContext(CartContext);
    if (context === undefined) {
        throw new Error('useCart must be used within a CartProvider');
    }
    return context;
};