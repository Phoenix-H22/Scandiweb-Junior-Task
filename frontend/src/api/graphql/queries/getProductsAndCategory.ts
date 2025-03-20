import {gql} from "@apollo/client";
export const GET_PRODUCTS_AND_CATEGORY = gql`
  query GetProductsAndCategory($categoryId: Int) {
    categories(id: $categoryId) {
      name
    }
    products(categoryId: $categoryId) {
      id
      name
      category {
        id
        name
      }
      description
      brand
      in_stock
      gallery
      prices {
        amount
        currency_label
        currency_symbol
      }
      attributes {
        name
        values
      }
      created_at
    }
  }
`;