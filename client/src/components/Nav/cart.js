// import React from 'react';
// import { useCart } from 'react-use-cart';

// const Cart = () => {
//     const {
//         isEmpty,
//         totalUniqueItems,
//         items,
//         totalItems,
//         cartTotal,
//         updateItemQuantity,
//         removeItem,
//         emptyCart,
//     } = useCart();

//     if (isEmpty) return <h1>Panier vide !</h1>
    
//     return (
//         <div>
//             <table>
//                 {items.map((value, key) => {
//                     <tr key={value.id}>
//                         <td>
//                             <img src={value.img} />
//                         </td>
//                         <td>{value.title}</td>
//                         <td>{value.price}</td>
//                         <td>Quantity ({value.quantity})</td>
//                     </tr>
//                 })}
//             </table>
//         </div>
//     )
// }
// export default Cart;
