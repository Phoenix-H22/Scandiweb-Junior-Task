export * from './graphql/queries/getCategories';
export * from './graphql/queries/getProduct';
export * from './graphql/queries/getProductsAndCategory';
export * from './graphql/mutations/createOrder';
export * from './types';
export { default as client } from './apollo/client';
export { ApolloProvider,useQuery } from "@apollo/client";
