import {gql} from "@apollo/client";
export const CREATE_ORDER = gql`
  mutation CreateOrder($items: [OrderItemInput!]!, $total: Float!, $currency: String!) {
    createOrder(
      items: $items,
      total: $total,
      currency: $currency
    )
  }
`;