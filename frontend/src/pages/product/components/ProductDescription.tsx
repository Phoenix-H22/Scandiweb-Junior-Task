interface ProductDescriptionProps {
    description: string;
}

export function ProductDescription({ description }: ProductDescriptionProps) {
    return (
        <div
            data-testid="product-description"
            className="max-h-[200px] overflow-y-auto border p-4 rounded-lg bg-gray-50 text-gray-700 hide-scrollbar"
        >
            {description}
        </div>
    );
}