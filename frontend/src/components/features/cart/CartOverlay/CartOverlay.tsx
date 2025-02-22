import { useCallback, useEffect, useState } from "react";
import { cn } from "../../../../lib/utils";
import { useCart } from "../../../../hooks/useCart.ts";
import { CartItem } from "../CartItem";
import { CREATE_ORDER,client,OrderItemInput } from "../../../../api";
import { useClickOutside } from "../../../../hooks/useClickOutside";
import {showToast} from "../../../../utils/toast.ts";

interface CartOverlayProps {
    open: boolean;
    onClose: () => void;
}

const generateCartItemId = (id: string, attributes: Array<{ name: string; selectedValue: string; values: string[] }>) => {
    const sortedAttributes = attributes
        .sort((a, b) => a.name.localeCompare(b.name))
        .map(attr => `${attr.name}:${attr.selectedValue}`)
        .join('|');
    return `${id}-${sortedAttributes}`;
};

export default function CartOverlay({ open, onClose }: CartOverlayProps) {
    const { cart, updateQuantity, totalItems, totalPrice, clearCart } = useCart();
    const [isPlacingOrder, setIsPlacingOrder] = useState(false);

    useClickOutside(open, onClose, '[data-testid="cart-btn"]');

    const handleEscKey = useCallback((event: KeyboardEvent) => {
        if (event.key === 'Escape' && open) {
            onClose();
        }
    }, [open, onClose]);

    useEffect(() => {
        if (open) {
            document.addEventListener('keydown', handleEscKey);
        }
        return () => {
            document.removeEventListener('keydown', handleEscKey);
        };
    }, [open, handleEscKey]);

    const handlePlaceOrder = async () => {
        if (!cart || cart.length === 0) return;

        setIsPlacingOrder(true);
        try {
            const loadingToast = showToast.loading('Placing your order...');

            const formattedItems: OrderItemInput[] = cart.map(item => ({
                productId: item.id,
                quantity: item.quantity,
                attributes: item.attributes.map(attr => ({
                    name: attr.name,
                    value: attr.selectedValue
                }))
            }));

            const { data, errors } = await client.mutate<{ createOrder: string }>({
                mutation: CREATE_ORDER,
                variables: {
                    items: formattedItems,
                    total: totalPrice,
                    currency: "USD"
                }
            });

            if (errors) {
                showToast.dismiss(loadingToast);
                throw new Error(errors[0].message);
            }

            if (data?.createOrder) {
                showToast.dismiss(loadingToast);
                showToast.success(`Order #${data.createOrder} placed successfully!`);
                clearCart();
                onClose();
            } else {
                showToast.dismiss(loadingToast);
                throw new Error('No order ID returned from server');
            }
        } catch (error: unknown) {
            console.error('Failed to place order:', error);
            if (error instanceof Error) {
                showToast.error(`Failed to place order: ${error.message}`);
            } else {
                showToast.error('Failed to place order. Please try again');
            }
        } finally {
            setIsPlacingOrder(false);
        }
    };

    return (
        <>
            <div
                onClick={(e) => {
                    e.stopPropagation();
                    onClose();
                }}
                className={cn(
                    "fixed left-0 bottom-0 z-40 bg-black/30 w-screen h-[calc(100vh-115px)] sm:h-[calc(100vh-80px)]",
                    open ? "block" : "hidden"
                )}
            />
            <div
                data-testid="cart-overlay"
                onClick={(e) => e.stopPropagation()}
                className={cn(
                    "absolute right-2 top-12 sm:top-8 z-50 text-left h-[500px] sm:h-[633px] w-[280px] sm:w-[400px] bg-white shadow-lg p-6 overflow-y-auto",
                    open ? "block" : "hidden"
                )}
            >
                <h2 className="text-xl font-semibold mb-6">
                    My Bag, <span className="font-normal">{totalItems} {totalItems === 1 ? 'Item' : 'Items'}</span>
                </h2>

                <div className="space-y-6">
                    {cart && cart.length > 0 && cart.map((item) => (
                        <CartItem
                            key={generateCartItemId(item.id, item.attributes)}
                            item={item}
                            onUpdateQuantity={updateQuantity}
                        />
                    ))}
                </div>

                <div className="mt-6 border-t pt-4">
                    <div className="flex justify-between text-lg font-semibold mb-4" data-testid="cart-total">
                        <span>Total</span>
                        <span>${totalPrice.toFixed(2)}</span>
                    </div>
                    <button
                        onClick={handlePlaceOrder}
                        disabled={!cart || cart.length === 0 || isPlacingOrder}
                        className={`w-full py-3 rounded transition-colors ${
                            !cart || cart.length === 0 || isPlacingOrder
                                ? 'bg-gray-400 cursor-not-allowed'
                                : 'bg-[#5ECE7B] hover:bg-[#4eb369]'
                        } text-white`}
                    >
                        {isPlacingOrder ? 'PLACING ORDER...' : 'PLACE ORDER'}
                    </button>
                </div>
            </div>
        </>
    );
}