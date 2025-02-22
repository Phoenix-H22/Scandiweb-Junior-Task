import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import './styles/globals.css';
import './styles/tailwind.css';
import { BrowserRouter } from "react-router-dom";
import { CategoryProvider } from "./hooks/useCategory.ts";
import { CartProvider } from "./hooks/useCart.ts";
import { ApolloProvider } from "@apollo/client";
import { client } from "./api";
import AppRoutes from "./routes";
import {CartOverlayProvider} from "./contexts/cart/useCartOverlay.tsx";

createRoot(document.getElementById("root")!).render(
    <StrictMode>
        <ApolloProvider client={client}>
            <CategoryProvider>
                <CartProvider>
                    <CartOverlayProvider>
                        <BrowserRouter>
                            <AppRoutes />
                        </BrowserRouter>
                    </CartOverlayProvider>
                </CartProvider>
            </CategoryProvider>
        </ApolloProvider>
    </StrictMode>
);