import React, { useEffect, useState } from "react";
// import { useSelector, useDispatch } from 'react-redux';
// import { GetArticles } from "../../actions/articles.action";
// import axios from 'axios';
import { Items } from "../../Helpers/CatalogJson";
import { Container, Row, Col, Card } from 'react-bootstrap';
import { CartProvider, useCart } from "react-use-cart";


const CardArticles = () => {
    const [products, setProducts] = useState(JSON.parse(JSON.stringify(Items)));
    const [priceFilter, setPriceFilter] = useState("");
    const [brandFilter, setBrandFilter] = useState("");
    const [categoryFilter, setCategoryFilter] = useState("");
    // const [cart,setCart] = useState([]);
    // console.log(cart);

    const { addItem } = useCart();

    const filterProducts = () => {
        let filteredItems = JSON.parse(JSON.stringify(Items))

        if (priceFilter === "+") {
            filteredItems.sort((a, b) => {return parseFloat(b.price) - parseFloat(a.price)});
        } else if (priceFilter === "-") {
            filteredItems.sort((a, b) => {return parseFloat(a.price) - parseFloat(b.price)});
        }

        if (brandFilter !== "") { 
            filteredItems = filteredItems.filter(value => value.marque.includes(brandFilter));
        }

        if (categoryFilter !== "") {
            filteredItems = filteredItems.filter(value => value.category.includes(categoryFilter));
        }

        setProducts(filteredItems)
    }


    return (
        <Container>
            <div className="filtercontainer">
                <select name='price' id='price' className='categorie' onChange={(e) => setPriceFilter(e.target.value)} >
                    <option value="">Prix</option>
                    <option value='+'> Du + cher au - cher</option>
                    <option value='-'> Du - cher au + cher</option>
                </select>
                <select name="marque" id="marque" className="categorie" onChange={(e) => setBrandFilter(e.target.value)}>
                    <option value="">Marque</option>
                    <option value="Corsair"  >Corsair</option>
                    <option value="Geforce">Geforce</option>
                    <option value="Asus">Asus</option>
                    <option value="Gigabyte">Gigabyte</option>
                    <option value="Intel">Intel</option>
                    <option value="AMD">AMD</option>
                </select>
                <select name="marque" id="marque" className="categorie" onChange={(e) => setCategoryFilter(e.target.value)}>
                    <option value="">Catégorie</option>
                    <option value="Carte-graphique">Carte-graphique</option>
                    <option value="processeur">Processeur</option>
                    <option value="Carte-mère">Carte-mère</option>
                    <option value="RAM">RAM</option>
                </select>
                <button onClick={filterProducts}>Filtrer</button>
            </div>
            <Row className='row-wrap-catalog'>
                <Col className="product-card-container" md={6} lg={5} xl={3} >
                    {
                        products.map((value, key) => {
                            return (
                                <Card className='product-card rounded-0' key={value.id}>
                                    <Card.Img className='img-product' variant="top" src={value.img} />
                                    <Card.Body className="text-start">
                                        <Card.Title className='title-article'>
                                            <p>
                                                Titre:
                                                <span> {value.title} </span>
                                            </p>
                                            <p>
                                                Prix:
                                                <span> {value.price} </span>
                                            </p>
                                            <p>
                                                Note:
                                                <span> {value.stars} </span>
                                            </p>
                                        </Card.Title>
                                        <button className=" btn-articles btn-block mt-3" onClick={() => addItem(value)}>Acheter</button>
                                    </Card.Body>
                                </Card>
                            )
                        })
                    }
                </Col>
            </Row>
        </Container>
    );
}
export default CardArticles;

{/*const dispatch = useDispatch();
    const articles = useSelector((state) => state.articlesReducer)

    useEffect(() => {
        axios.get("http://localhost:8000/api/product/list")
            .then(res => {
                dispatch(GetArticles(res.data));
            }
            ).catch(err => console.log(err))
        }, []);*/}


{/* <div className="product-card-container">

{articles[0] &&
    articles.map((article) => {
        return (
            <li className="product-card img">
                <span className="img-product" />
                <span className="title-article">
                    <p>{article.name}</p>
                </span>
            </li>
        )

    })
}
</div> */}

// {() => setCart([...cart, value.img, value.title, value.price])}