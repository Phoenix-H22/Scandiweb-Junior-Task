import {StrictMode} from "react";
import {createRoot} from "react-dom/client";
import './styles/globals.css';
import {BrowserRouter} from "react-router-dom";
import {CategoryProvider} from "./hooks/useCategory.ts";
import {CartProvider} from "./hooks/useCart.ts";
import {ApolloProvider} from "@apollo/client";
import {client} from "./api";
import AppRoutes from "./routes";
import {CartOverlayProvider} from "./contexts/cart/useCartOverlay.tsx";
import {Toaster} from 'react-hot-toast';

createRoot(document.getElementById("root")!).render(
    <StrictMode>
        <ApolloProvider client={client}>
            <CategoryProvider>
                <CartProvider>
                    <CartOverlayProvider>
                        <BrowserRouter>
                            <AppRoutes/>
                            <Toaster
                                position="top-right"
                                toastOptions={{
                                    success: {
                                        style: {
                                            background: '#5ECE7B',
                                            color: 'white',
                                        },
                                        duration: 3000,
                                    },
                                    error: {
                                        style: {
                                            background: '#D12727',
                                            color: 'white',
                                        },
                                        duration: 3000,
                                    },
                                }}
                            />
                        </BrowserRouter>
                    </CartOverlayProvider>
                </CartProvider>
            </CategoryProvider>
        </ApolloProvider>
    </StrictMode>
);