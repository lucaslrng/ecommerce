import React, { useEffect, useState } from "react";
import { useSelector, useDispatch } from 'react-redux';
import { GetArticles, GetBrand } from "../../actions/articles.action";
import axios from 'axios';


const Card = () => {
    const dispatch = useDispatch();
    const articles = useSelector((state) => state.articlesReducer)
    const [filteredArticle, setFilteredArticle] = useState(articles);
    const [filteredPrice, setFilteredPrice] = useState(articles);

    useEffect(() => {
        axios.get("http://localhost:8000/api/product/list")
            .then(res => {
                dispatch(GetArticles(res.data));
            })
            .catch(err => console.log(err));
    }, []);

    const handleFilter = (e) => {
        const tab = JSON.parse(JSON.stringify(articles));
        const filter = e.target.value;
        const newTab = articles.filter((value) => {
            return value.name.includes(filter);
        });
        if (filter === '') {
            setFilteredArticle(tab);
        } else {
            setFilteredArticle(newTab);
            console.log(newTab);
        }
    }

    const handleFilterP = (e) => {
        const price = e.target.value;
        const tab = JSON.parse(JSON.stringify(articles));
        if (price === '') {
            const Sort = tab.sort((a, b) => {
                return b.id - a.id;
            })
            setFilteredPrice(Sort);
            console.log(Sort);
        }
        if (price === '+') {
            const ascSort = tab.sort((a, b) => {
                return b.price - a.price;
            })
            setFilteredPrice(ascSort);
            console.log(ascSort);
        }
        if (price === '-') {
            const descSort = tab.sort((a, b) => {
                return a.price - b.price;
            })
            setFilteredPrice(descSort);
            console.log(descSort);
        }
    }

    return (
        <>
            <div className="filtercontainer">
                <form>
                    <select name='price' id='price' className='categorie' onChange={handleFilterP}>
                        <option value="">Prix</option>
                        <option value='+'> Du + cher au - cher</option>
                        <option value='-'> Du - cher au + cher</option>
                    </select>
                    <select name="marque" id="marque" className="categorie" onChange={handleFilter}>
                        <option value="">Marque</option>
                        <option value="Corsair"  >Corsair</option>
                        <option value="Kingston">Kingston</option>
                        <option value="Textorm">Textorm</option>
                        <option value="G.Skill">G.Skill</option>
                        <option value="ASRock">ASRock</option>
                        <option value="Asus">Asus</option>
                        <option value="Gigabyte">Gigabyte</option>
                        <option value="Intel">Intel</option>
                        <option value="AMD">AMD</option>
                    </select>
                </form>
            </div>
            <div className="product-card-container">
                {/* {
                    filteredArticle ?
                        filteredArticle.map((value, key) => {
                            return <li className="product-card img"><span className="img-product" /><span className="title-article"><p>{value.name} - <strong>{value.price}€</strong></p> </span></li>
                        }) :
                        articles.map((article) => {
                            return <li className="product-card img"><span className="img-product" /><span className="title-article"><p>{article.name} - <strong>{article.price}€</strong></p> </span></li>
                        })
                } */}
                {
                    filteredPrice ?
                    filteredPrice.map((value, key) => {
                            return <li className="product-card img"><span className="img-product" /><span className="title-article"><p>{value.name} - <strong>{value.price}€</strong></p> </span></li>
                        }) :
                        articles.map((article) => {
                            return <li className="product-card img"><span className="img-product" /><span className="title-article"><p>{article.name} - <strong>{article.price}€</strong></p> </span></li>
                        })
                }
            </div>
        </>
    );
}
export default Card;