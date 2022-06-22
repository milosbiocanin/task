import React, {useState, useRef} from 'react';
import { Modal, Button, Row, Col } from 'react-bootstrap';
import {config} from './config'
import {GET, POST} from "../service/service"

function CategoryDetail(props) {
	const {
		closeDlg,
    category,
    categories,
		setCategory,
		addCategory
	} = props
  const nameRef = useRef()
	const [catInfo, setCatInfo] = useState(category?category:{})
	const modifyCatInfo = (e)=>{
		const newInfo = {...catInfo, [e.target.name]: e.target.value}
		setCatInfo(newInfo)
	}

	const saveCategory=()=>{
		nameRef.current.classList.remove('is-invalid')
		if(!catInfo?.name || catInfo.name.trim()===''){
			nameRef.current.classList.add('is-invalid')
			return false;
		}

		POST(`${config.apiUrl}admin/categories/create`, catInfo).then(res => {
			if(res.data.status==='success'){
				const modified = {...catInfo, id: res.data.category}
				setCatInfo(modified)
				if(category){
					setCategory(modified)
				}else{
					addCategory(modified)
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
                name='parent_id'
                className="form-control form-control-sm"
                onChange={modifyCatInfo}
                defaultValue={catInfo?.parent_id}
              >
                <option value=''>None</option>
                {categories && categories.map((cat, index) => (
                <option value={cat.id} key={index}>{cat.name}</option>
                ))}
              </select>
            </div>
          </Col>
          
        </Row>
			</Modal.Body>
			<Modal.Footer>
				<Button variant="secondary" onClick={closeDlg}>Close</Button>
				<Button variant="primary" onClick={saveCategory}>Save</Button>
			</Modal.Footer>
		</>
	);
}

export default CategoryDetail;