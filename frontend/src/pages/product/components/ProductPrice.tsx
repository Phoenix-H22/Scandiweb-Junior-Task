import {Price} from "../../../api";

interface ProductPriceProps {
    price: Price;
}
export function ProductPrice({price}: ProductPriceProps) {
    return (
        <div className="space-y-2">
            <label className="text-sm font-medium uppercase text-gray-700">
                PRICE:
            </label>
            <p className="text-2xl font-bold">
                {price.currency_symbol}
                {price.amount.toFixed(2)}
            </p>
        </div>
    );
}