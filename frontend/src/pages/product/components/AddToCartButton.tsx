import {cn} from "../../../lib/utils.ts";
interface AddToCartButtonProps {
    inStock: boolean;
    isValid: boolean;
    onClick: () => void;
}
export function AddToCartButton({inStock, isValid, onClick}: AddToCartButtonProps) {
    const isDisabled = !inStock || !isValid;

    return (
        <button
            onClick={onClick}
            disabled={isDisabled}
            data-testid="add-to-cart"
            className={cn(
                "w-full py-4 text-white rounded-md transition",
                !isDisabled
                    ? "bg-green-500 hover:bg-green-600"
                    : "bg-gray-400 cursor-not-allowed"
            )}
        >
            {inStock ? "ADD TO CART" : "OUT OF STOCK"}
        </button>
    );
}