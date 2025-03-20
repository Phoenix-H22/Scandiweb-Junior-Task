import {gql} from "@apollo/client";
export const GET_PRODUCT = gql`
  query GetProduct($id: String!) {
    product(id: $id) {
      id
      name
      description
      category {
        id
        name
      }
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