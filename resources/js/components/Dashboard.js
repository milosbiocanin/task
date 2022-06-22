import React, {useEffect,useState} from 'react';
import ReactDOM from 'react-dom';
import { Modal, Row, Col } from 'react-bootstrap';
import {config} from './config'
import {GET, POST} from "../service/service"
import ProductDetail from './ProductDetail';

function Dashboard(props) {
	const [show, setShow] = useState()
  const [products, setProducts] = useState([])
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
      const add = (accumulator, prod) => {
        return accumulator + parseFloat(prod?.price) * parseInt(prod?.count);
      }
      return (
        <>
        {curProducts.length > 0 && (
          <div className='row mx-0'>
            <div className='col-10 px-0'>
            {curProducts.map((prod, index) => (
              <div className='product-row row mx-0' key={index}>
                <div className={`col-3 pll-${level}`}>{prod?.name}</div>
                <div className='col-3'>{prod?.count}</div>
                <div className='col-3'>{prod?.price}</div>
                <div className='col-3'>{parseFloat(prod?.price) * parseInt(prod?.count)}</div>
              </div>
            ))}
            </div>
            <div className='col-2 px-0 d-flex align-items-center justify-content-center'>
              <span>{curProducts.reduce(add , 0)}</span>
            </div>
          </div>
        )}
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
              <div className='row mx-0'>
                <div className='col-2 px-0 offset-10 total-price text-center'>
                  {products.reduce((partialSum, prod) => partialSum + parseFloat(prod?.price) * parseInt(prod?.count), 0)}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Modal show={show} onHide={handleClose} size="lg">
      <Modal.Header closeButton>
        <Modal.Title>New Product</Modal.Title>
      </Modal.Header>
      <ProductDetail closeDlg={handleClose} addProduct={addProduct} categories={categories}/>

    </Modal>
    </div>
  );
}

export default Dashboard;

if (document.getElementById('dashboard')) {
	ReactDOM.render(<Dashboard />, document.getElementById('dashboard'));
}
