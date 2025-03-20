import {createContext, useContext, useState} from 'react';

interface CartOverlayContextType {
    isCartOpen: boolean;
    setIsCartOpen: (isOpen: boolean) => void;
}

const CartOverlayContext = createContext<CartOverlayContextType | undefined>(undefined);

export function CartOverlayProvider({children}: { children: React.ReactNode }) {
    const [isCartOpen, setIsCartOpen] = useState(false);

    return (
        <CartOverlayContext.Provider value={{isCartOpen, setIsCartOpen}}>
            {children}
        </CartOverlayContext.Provider>
    );
}

export function useCartOverlay() {
    const context = useContext(CartOverlayContext);
    if (context === undefined) {
        throw new Error('useCartOverlay must be used within a CartOverlayProvider');
    }
    return context;
}