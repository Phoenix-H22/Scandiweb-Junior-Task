import {createContext} from "react";

interface CategoryContextType {
    categoryName: string;
    setCategoryName: (name: string) => void;
}

export const CategoryContext = createContext<CategoryContextType | undefined>(undefined);
