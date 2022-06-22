import React, {useState, useRef} from 'react';
import { Modal, Button, Row, Col } from 'react-bootstrap';
import {config} from './config'
import {GET, POST} from "../service/service"

function ProductDetail(props) {
	const {
		closeDlg,
    product,
    categories,
		setProduct,
		addProduct
	} = props
  const nameRef = useRef()
  const priceRef = useRef()
  const countRef = useRef()
	const [catInfo, setCatInfo] = useState(product?product:{count: 1})
	const modifyCatInfo = (e)=>{
		const newInfo = {...catInfo, [e.target.name]: e.target.value}
		setCatInfo(newInfo)
	}

	const saveProduct=()=>{
    let productData = {...catInfo}
		nameRef.current.classList.remove('is-invalid')
		if(!catInfo?.name || catInfo.name.trim()===''){
			nameRef.current.classList.add('is-invalid')
			return false;
		}

    priceRef.current.classList.remove('is-invalid')
		if(!catInfo?.price || !parseFloat(catInfo?.price)){
			priceRef.current.classList.add('is-invalid')
			return false;
		}

    countRef.current.classList.remove('is-invalid')
		if(!catInfo?.count || !parseInt(catInfo?.count)){
			countRef.current.classList.add('is-invalid')
			return false;
		}

    if(!catInfo?.category_id){
			productData = {...productData, category_id: categories?.[0]?.id||1}
      setCatInfo(productData)
		}

		POST(`${config.apiUrl}admin/product/create`, productData).then(res => {
			if(res.data.status==='success'){
				const modified = {...catInfo, id: res.data.product}
				setCatInfo(modified)
				if(product){
					setProduct(modified)
				}else{
					addProduct(modified)
				}
				closeDlg()
			}
		}).catch(err => {});
	}
	return (
		<>
			<Modal.Body>
        <Row className="form-group">
          <Col sm={12}>
            <div className="form-group">
              <label className="form-label">Name</label>
              <input ref={nameRef} type="text" className="form-control form-control-sm" name="name" onChange={modifyCatInfo} value={catInfo?.name}/>
            </div>
          </Col>
          <Col sm={12}>
            <div className="form-group">
              <label className="form-label">Parent</label>
              <select
                name='category_id'
                className="form-control form-control-sm"
                onChange={modifyCatInfo}
                value={catInfo?.category_id}
              >
                {categories && categories.map((cat, index) => (
                <option value={cat.id} key={index}>{cat.name}</option>
                ))}
              </select>
            </div>
          </Col>
          <Col sm={12}>
            <div className="form-group">
              <label className="form-label">Price</label>
              <input ref={priceRef} type="text" className="form-control form-control-sm" name="price" onChange={modifyCatInfo} value={catInfo?.price}/>
            </div>
          </Col>
          <Col sm={12}>
            <div className="form-group">
              <label className="form-label">Count</label>
              <input ref={countRef} type="number" min={1} className="form-control form-control-sm" name="count" onChange={modifyCatInfo} value={catInfo?.count}/>
            </div>
          </Col>
        </Row>
			</Modal.Body>
			<Modal.Footer>
				<Button variant="secondary" onClick={closeDlg}>Close</Button>
				<Button variant="primary" onClick={saveProduct}>Save</Button>
			</Modal.Footer>
		</>
	);
}

export default ProductDetail;