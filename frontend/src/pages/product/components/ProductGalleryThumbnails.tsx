import {cn} from "../../../lib/utils.ts";

interface ProductGalleryThumbnailsProps {
    gallery: string[];
    currentIndex: number;
    onSelect: (index: number) => void;
}

export function ProductGalleryThumbnails({ gallery, currentIndex, onSelect }: ProductGalleryThumbnailsProps) {
    return (
        <div className="hidden md:block w-[200px] h-[600px] overflow-hidden">
            <div className="h-full overflow-y-auto pr-4 hide-scrollbar">
                <div className="flex flex-col gap-4">
                    {gallery.map((src, index) => (
                        <button
                            key={index}
                            onClick={() => onSelect(index)}
                            className={cn(
                                "cursor-pointer relative w-full h-48 border-2 rounded-lg overflow-hidden transition-all hover:opacity-80",
                                currentIndex === index ? 'border-black' : 'border-gray-300'
                            )}
                        >
                            <img
                                src={src}
                                alt={`Product thumbnail ${index + 1}`}
                                className="h-full w-full object-cover"
                            />
                        </button>
                    ))}
                </div>
            </div>
        </div>
    );
}