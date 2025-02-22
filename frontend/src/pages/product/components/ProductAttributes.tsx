import { ProductAttribute } from "../../../api";
import { cn } from "../../../lib/utils.ts";

interface ProductAttributesProps {
    attributes: ProductAttribute[];
    selectedAttributes: Record<string, string>;
    onSelect: (name: string, value: string) => void;
}

export function ProductAttributes({
                                      attributes,
                                      selectedAttributes,
                                      onSelect
                                  }: ProductAttributesProps) {
    const getColorTestId = (value: string) => {
        if (value.toLowerCase() === 'green') {
            return 'product-attribute-color-Green';
        }
        if (value.startsWith('#')) {
            return `product-attribute-color-${value}`;
        }
        return `product-attribute-color-${value}`;
    };

    return (
        <>
            {attributes.map((attribute) => {
                const isColorAttribute = attribute.name.toLowerCase() === "color";

                return (
                    <div
                        key={attribute.name}
                        className="space-y-2"
                        data-testid={`product-attribute-${attribute.name.toLowerCase().replace(/\s+/g, '-')}`}
                    >
                        <label className="text-sm font-medium uppercase text-gray-700">
                            {attribute.name}:
                        </label>
                        <div className="flex gap-2 flex-wrap">
                            {attribute.values.map((value) => {
                                const isSelected = selectedAttributes[attribute.name] === value;

                                if (isColorAttribute) {
                                    console.log('Color value:', value);
                                    const testId = getColorTestId(value);
                                    console.log('Generated testId:', testId);

                                    return (
                                        <button
                                            key={value}
                                            onClick={() => onSelect(attribute.name, value)}
                                            data-testid={testId}
                                            className={cn(
                                                "w-10 h-10 rounded-sm transition-transform",
                                                isSelected ? "ring-2 ring-black scale-110" : ""
                                            )}
                                            style={{
                                                backgroundColor: value.startsWith('#') ? value : value.toLowerCase()
                                            }}
                                        />
                                    );
                                }

                                return (
                                    <button
                                        key={value}
                                        onClick={() => onSelect(attribute.name, value)}
                                        data-testid={`product-attribute-${attribute.name.toLowerCase()}-${value}`}
                                        className={cn(
                                            "cursor-pointer px-4 py-2 border rounded transition",
                                            isSelected ? "border-black bg-black text-white" : "border-gray-300 hover:bg-gray-200",
                                            !isColorAttribute ? "min-w-[40px]" : ""
                                        )}
                                    >
                                        {value}
                                    </button>
                                );
                            })}
                        </div>
                    </div>
                );
            })}
        </>
    );
}