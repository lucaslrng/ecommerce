import React from 'react';
import '../Styles/pages/article.scss';
import { Container, Col, Row } from 'react-bootstrap';
import proc from '../assets/images/articles/processeurV2.png';

function Article() {
    return (
        <div className='pageContainer'>
            <Container>
                <div className="articleContainer">
                    <Row>
                        
                        <Col xs={6}>
                            <div className="leftSide">
                                <img src={proc} alt='processeur' />
                            </div>
                        </Col>
                        <Col xs={6}>
                            <div className="rightSide">
                                <div className="descritpion">
                                    <h1>Description</h1>
                                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quam mollitia voluptatem, ratione natus quia reiciendis? Veritatis ab maxime natus? Ipsum, consequatur optio. Numquam quisquam atque omnis voluptatum tempore, nisi veniam? Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eius ut nobis non sunt quisquam reprehenderit voluptate tenetur! Illo ducimus veniam, eveniet, id, expedita dolore libero facilis reprehenderit doloremque autem debitis.</p>
                                </div>
                                <div className="info">
                                    <span><h3 className='price'>999€</h3></span>
                                    <span className='stock'><h3>Disponibilité: <strong className='stat'>En stock</strong></h3></span>
                                </div>
                                <div className="shop">
                                    <button className='btn-shop btn-block'>Ajouter au panier</button>
                                </div>
                            </div>
                        </Col>
                    </Row>
                </div>
            </Container>
        </div>
    )
}

export default Article