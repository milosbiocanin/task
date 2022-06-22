import React, {useEffect,useState} from 'react';
import ReactDOM from 'react-dom';
import { Modal, Row, Col } from 'react-bootstrap';
import {config} from './config'
import {GET, DELETE} from "../service/service"
import CategoryDetail from './CategoryDetail';

function Categories(props) {
  const [show, setShow] = useState()
  const [categories, setCategories] = useState([])
  const [currentCategory, setCurrentCategory] = useState()
  const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  });

  useEffect(()=>{
    GET(`${config.apiUrl}admin/categories/list`).then(res => {
      if(res.data.status==='success'){
        console.log(res.data.categories)
        setCategories(res.data.categories)
      }
    }).catch(err => {});
    
  },[])

  const getParentName = (id) => {
    if (categories.length > 0 && id) {
      const parentCat = categories.filter(cat => cat.id == id)
      if (parentCat.length > 0) {
        return parentCat[0].name
      }
    }
    return 'None'
  }

  const handleClose = () => {
    setShow(false)
  }

  const handleShow = () => {
    setShow(true)
  }
  const addCategory = (cat) => {
    const newCats = [...categories, cat]
    setCategories(newCats)
  }

  const setCategory = (cat) => {
    
    let newCats = [...categories]
    for(let i=0; i < newCats.length ; i++) {
      if (newCats[i].id === cat.id) {
        newCats[i] = {...cat}
      }
    }
    setCategories(newCats)
  }

  const handleRemoveCategory = (cat) => {
    DELETE(`${config.apiUrl}admin/categories/remove/${cat.id}`).then(res => {
      if(res.data.status==='success'){
        const newCats = categories.filter(pd => pd.id != cat.id )
        setCategories(newCats)
      }
    }).catch(err => {});
  }

  const handleEditCategory = (cat) => {
    setCurrentCategory(cat)
    setShow(true)
  }

  return (
    <div>
      <div className="header bg-primary pb-6  pt-7">
        <div className="container-fluid">
          <div className="header-body">
            <div className="row align-items-center py-4">
              <div className="col-lg-6 col-7">
                <h6 className="h2 text-white d-inline-block mb-0">Categories</h6>
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
          <div className="card">

            <div className="table-responsive">
              <table className="table align-items-center table-flush">
                <thead className="thead-light">
                  <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Parent</th>
                  <th scope="col" ></th>
                  </tr>
                </thead>
                <tbody className="list">
                {categories && categories.map((cat, index) => (
                  <tr key={index}>
                    <td>{cat?.name}</td>
                    <td>{getParentName(cat?.parent_id)}</td>
                    <td>
                      <button className='btn btn-danger btn-sm' onClick={() => handleRemoveCategory(cat)}>
                        <i className='fa fa-trash' />
                      </button> 
                      <button className='btn btn-primary btn-sm ml-2' onClick={() => handleEditCategory(cat)}>
                        <i className='fa fa-edit' />
                      </button> 
                    </td>
                  </tr>
                ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Modal show={show} onHide={handleClose} size="lg">
      <Modal.Header closeButton>
        <Modal.Title>New Category</Modal.Title>
      </Modal.Header>
      <CategoryDetail
        closeDlg={handleClose}
        addCategory={addCategory}
        setCategory={setCategory}
        categories={categories}
        category={currentCategory}
      />

    </Modal>
    </div>
  );
}

export default Categories;

if (document.getElementById('categories')) {
  ReactDOM.render(<Categories me={document.getElementById('categories').dataset.me}/>, document.getElementById('categories'));
}
