import { CartItem as ICartItem } from "../../../../contexts/cart/CartContext";
interface CartItemProps {
  item: ICartItem;
  onUpdateQuantity: (id: string, attributes: Record<string, string>, increment: boolean) => void;
}
export default function CartItem({ item, onUpdateQuantity }: CartItemProps) {
  const attributesRecord = Object.fromEntries(
    item.attributes.map((attr) => [attr.name, attr.selectedValue])
  );

  return (
    <div className="flex gap-4" data-testid={`cart-item-${item.id}`}>
      <div className="flex-1">
        <h3 className="font-medium">{item.name}</h3>
        <p className="text-lg font-semibold">${item.price.toFixed(2)}</p>

        {item.attributes.map((attr) => (
          <div
            key={attr.name}
            className="mt-2"
            data-testid={`cart-item-attribute-${attr.name.toLowerCase().replace(" ", "-")}`}
          >
            <p className="text-sm mb-2">{attr.name}:</p>
            <div className="flex gap-2">
              {attr.values.map((value) => {
                const isColorAttribute = attr.name.toLowerCase() === "color";
                const isSelected = attr.selectedValue === value;

                if (isColorAttribute) {
                  return (
                    <div
                      key={value}
                      data-testid={`cart-item-attribute-${attr.name
                        .toLowerCase()
                        .replace(" ", "-")}-${value.toLowerCase().replace(" ", "-")}${
                        isSelected ? "-selected" : ""
                      }`}
                      className={`w-8 h-8 rounded-sm ${isSelected ? "ring-2 ring-black" : ""}`}
                      style={{ backgroundColor: value }}
                    />
                  );
                }

                return (
                  <div
                    key={value}
                    data-testid={`cart-item-attribute-${attr.name
                      .toLowerCase()
                      .replace(" ", "-")}-${value.toLowerCase().replace(" ", "-")}${
                      isSelected ? "-selected" : ""
                    }`}
                    className={`min-w-[32px] h-8 px-2 border flex items-center justify-center text-sm ${
                      isSelected
                        ? "border-black bg-black text-white"
                        : "border-gray-200 bg-white text-black"
                    }`}
                  >
                    {value}
                  </div>
                );
              })}
            </div>
          </div>
        ))}
      </div>

      <div className="flex flex-col justify-between items-center">
        <button
          data-testid="cart-item-amount-increase"
          onClick={() => onUpdateQuantity(item.id, attributesRecord, true)}
          className="w-8 h-8 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors"
        >
          +
        </button>
        <span data-testid="cart-item-amount" className="text-lg font-medium">
          {item.quantity}
        </span>
        <button
          data-testid="cart-item-amount-decrease"
          onClick={() => onUpdateQuantity(item.id, attributesRecord, false)}
          className="w-8 h-8 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors"
        >
          -
        </button>
      </div>

      <div className="w-24 h-40 relative">
        <img
          src={item.image}
          alt={item.name}
          className="absolute w-full h-full object-contain rounded-md"
        />
      </div>
    </div>
  );
}
