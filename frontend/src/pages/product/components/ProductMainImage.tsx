import {ChevronLeft, ChevronRight} from "lucide-react";

interface ProductMainImageProps {
    image: string;
    name: string;
    hasMultipleImages: boolean;
    onPrev: () => void;
    onNext: () => void;
}

export function ProductMainImage({
                                     image,
                                     name,
                                     hasMultipleImages,
                                     onPrev,
                                     onNext
                                 }: ProductMainImageProps) {
    return (
        <div className="relative flex-1 h-[600px] flex items-center justify-center">
            <img
                src={image}
                alt={name}
                className="h-full w-full object-contain"
            />
            {hasMultipleImages && (
                <>
                    <button
                        onClick={onPrev}
                        className="absolute left-2 top-1/2 -translate-y-1/2 bg-black/60 text-white p-2 shadow-lg rounded-full hover:bg-black/80 transition-colors"
                    >
                        <ChevronLeft className="h-6 w-6"/>
                    </button>
                    <button
                        onClick={onNext}
                        className="absolute right-2 top-1/2 -translate-y-1/2 bg-black/60 text-white p-2 shadow-lg rounded-full hover:bg-black/80 transition-colors"
                    >
                        <ChevronRight className="h-6 w-6"/>
                    </button>
                </>
            )}
        </div>
    );
}