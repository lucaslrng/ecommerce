import { combineReducers } from "redux";
import userReducer from'./user.reducer';
import articlesReducer from "./articles.reducer";
import addressReducer from "./address.reducer";

export default combineReducers({
    userReducer,
    articlesReducer,
    addressReducer,
})