import { ApolloClient, InMemoryCache } from "@apollo/client";

const client = new ApolloClient({
    uri: `${import.meta.env.VITE_BASE_URL}/graphql`, // Replace with your GraphQL API URL
    cache: new InMemoryCache(),
});

export default client;
