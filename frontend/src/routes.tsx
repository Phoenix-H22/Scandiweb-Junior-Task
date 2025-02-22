import {Navigate, Route, Routes} from "react-router-dom";
import { useQuery } from "@apollo/client";
import {Category, GET_CATEGORIES} from "./api";
import { MainLayout } from "./components/layouts/MainLayout";
import { ProductDetails } from "./pages/product";
import { ProductList } from "./components/features/products/ProductList";

function AppRoutes() {
    const {loading, error, data} = useQuery(GET_CATEGORIES);

    if (loading || error) {
        return (
            <Routes>
                <Route element={<MainLayout/>}>
                    <Route path="/" element={<Navigate to="/all" replace />} />
                    <Route path="/all" element={<ProductList />} />
                    <Route path="*" element={<ProductList />} />
                </Route>
            </Routes>
        );
    }

    return (
        <Routes>
            <Route element={<MainLayout/>}>
                <Route path="/" element={<Navigate to="/all" replace />} />
                {data?.categories.map((category: Category) => (
                    <Route
                        key={category.id}
                        path={`/${category.name.toLowerCase()}`}
                        element={<ProductList />}
                    />
                ))}
                <Route path="/product-details/:id" element={<ProductDetails />} />
            </Route>
        </Routes>
    );
}

export default AppRoutes;