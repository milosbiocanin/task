import React, {useEffect,useState} from 'react';
import ReactDOM from 'react-dom';
import { Modal, Row, Col } from 'react-bootstrap';
import {config} from './config'
import {GET, DELETE} from "../service/service"
import ProductDetail from './ProductDetail';

function Products(props) {
	const [show, setShow] = useState()
  const [products, setProducts] = useState([])
  const [currentProduct, setCurrentProduct] = useState()
  const [categories, setCategories] = useState([])
  const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  });

  useEffect(()=>{
    GET(`${config.apiUrl}admin/product/list`).then(res => {
      if(res.data.status==='success'){
        console.log(res.data.products)
        setProducts(res.data.products)
      }
    }).catch(err => {});
    GET(`${config.apiUrl}admin/categories/list`).then(res => {
      if(res.data.status==='success'){
        setCategories(res.data.categories)
      }
    }).catch(err => {});
  },[])

  const handleClose = () => {
    setShow(false)
  }

  const handleShow = () => {
    setShow(true)
  }
  const addProduct = (prod) => {
    const newProds = [...products, prod]
    setProducts(newProds)
  }

  const setProduct = (prod) => {
    
    let newProds = [...products]
    for(let i=0; i < newProds.length ; i++) {
      if (newProds[i].id === prod.id) {
        newProds[i] = {...prod}
      }
    }
    setProducts(newProds)
    handleClose()
  }

  const handleRemoveProduct = (prod) => {
    DELETE(`${config.apiUrl}admin/product/remove/${prod.id}`).then(res => {
      if(res.data.status==='success'){
        const newProds = products.filter(pd => pd.id != prod.id )
        setProducts(newProds)
      }
    }).catch(err => {});
  }

  const handleEditProduct = (prod) => {
    setCurrentProduct(prod)
    setShow(true)
  }

  const renderTable = (id, level=0) => {
    if (!id) {
      return (
        <>
        {categories.filter(cat => !cat.parent_id).map((cat, index) => (
          <div className='product-row' key={index}>
            <div className='cat-row'><span>{cat.name}</span></div>
            {renderTable(cat.id, level+1)}
          </div>
        ))}
        </>
      )
    } else {
      const curProducts = products.filter(prod => prod.category_id == id)
      const subCats = categories.filter(cat => cat.parent_id == id)
      return (
        <>
        {curProducts.length > 0 && curProducts.map((prod, index) => (
          <div className='product-row row mx-0' key={index}>
            <div className={`col-3 pll-${level}`}>{prod?.name}</div>
            <div className='col-3'>{prod?.count}</div>
            <div className='col-3'>{prod?.price}</div>
            <div className='col-3'>
              <button className='btn btn-danger btn-sm' onClick={() => handleRemoveProduct(prod)}>
                <i className='fa fa-trash' />
              </button> 
              <button className='btn btn-primary btn-sm ml-2' onClick={() => handleEditProduct(prod)}>
                <i className='fa fa-edit' />
              </button> 
            </div>
          </div>
        ))}
        {subCats.length > 0 && subCats.map((cat, index) => (
          <div className='product-row' key={index}>
            <div className={`cat-row pll-${level}`}><span>{cat.name}</span></div>
            {renderTable(cat.id, level+1)}
          </div>
        ))}
        </>
      )
    }
  }

  return (
    <div>
      <div className="header bg-primary pb-6  pt-7">
        <div className="container-fluid">
          <div className="header-body">
            <div className="row align-items-center py-4">
              <div className="col-lg-6 col-7">
                <h6 className="h2 text-white d-inline-block mb-0">Total</h6>
              </div>
              <div className="col-lg-6 col-5 text-right">
                <a className="btn btn-sm btn-neutral" onClick={()=>handleShow()}>New</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div className="container-fluid mt--6">
        <div className="row">
        <div className="col">
          <div className="card py-4">

            <div className="product-list container">
              <div className='product-fields product-row row'>
                <div className='col'>Data</div>
              </div>
              {renderTable()}
            </div>
          </div>
        </div>
      </div>
    </div>
    <Modal show={show} onHide={handleClose} size="lg">
      <Modal.Header closeButton>
        <Modal.Title>New Product</Modal.Title>
      </Modal.Header>
      <ProductDetail
        closeDlg={handleClose}
        addProduct={addProduct}
        setProduct={setProduct}
        categories={categories}
        product={currentProduct}
      />

    </Modal>
    </div>
  );
}

export default Products;

if (document.getElementById('products')) {
	ReactDOM.render(<Products />, document.getElementById('products'));
}
