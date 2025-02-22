interface ProductHeaderProps {
    name: string;
    brand: string;
}

export function ProductHeader({ name, brand }: ProductHeaderProps) {
    return (
        <div>
            <h1 className="text-3xl font-bold">{name}</h1>
            <p className="text-sm text-gray-500">{brand}</p>
        </div>
    );
}