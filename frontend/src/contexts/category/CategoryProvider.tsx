import {ReactNode, useState} from "react";
import {CategoryContext} from "./CategoryContext";


export function CategoryProvider({children}: { children: ReactNode }) {
    const [categoryName, setCategoryName] = useState("All");

    return (
        <CategoryContext.Provider value={{categoryName, setCategoryName}}>
            {children}
        </CategoryContext.Provider>
    );
}