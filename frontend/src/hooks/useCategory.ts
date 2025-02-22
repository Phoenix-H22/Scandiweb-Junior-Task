import {useContext} from "react";
import {CategoryContext} from "../contexts/category/CategoryContext.tsx";
export {CategoryProvider} from "../contexts/category/CategoryProvider.tsx";

export function useCategory() {
    const context = useContext(CategoryContext);
    if (context === undefined) {
        throw new Error("useCategory must be used within a CategoryProvider");
    }
    return context;
}