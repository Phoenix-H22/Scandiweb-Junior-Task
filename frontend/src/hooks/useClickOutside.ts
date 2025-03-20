import {useEffect, useCallback} from 'react';

export const useClickOutside = (
    isOpen: boolean,
    onClose: () => void,
    excludeSelector?: string
) => {
    const handleClickOutside = useCallback(
        (event: MouseEvent) => {
            const target = event.target as HTMLElement;
            if (
                isOpen &&
                !target.closest('[data-testid="cart-overlay"]') &&
                (!excludeSelector || !target.closest(excludeSelector))
            ) {
                onClose();
            }
        },
        [isOpen, onClose, excludeSelector]
    );

    useEffect(() => {
        if (isOpen) {
            document.addEventListener('mousedown', handleClickOutside);
        }
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [isOpen, handleClickOutside]);

    return handleClickOutside;
};