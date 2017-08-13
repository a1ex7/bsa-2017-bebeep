import React, { Component } from 'react';
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux';
import { filterPast,filterUpcoming } from "../actions";
import List from './List';
import './css/ListWithTripsItems.scss';

class ListWithTripsItems extends  Component{

    constructor(props) {
        super(props);
        this.props.filterPast();
    }

    render() {
        let trips  = this.props.tripsState.filtered;
        return (
            <div>
                <ul className="pagination d-flex justify-content-center">
                    <li className="page-item"><a className="page-link" href="#" onClick={this.props.filterPast}>Past trips</a></li>
                    <li className="page-item"><a className="page-link" href="#" onClick={this.props.filterUpcoming}>Upcoming trips</a></li>
                </ul>

                <List allTrips={trips}/>
            </div>
        );
    }
}

function mapStateToProps (state) {
    return {
        tripsState: state.tripsList
    }
}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({filterPast,filterUpcoming},dispatch);
}

export default connect(mapStateToProps,mapDispatchToProps)(ListWithTripsItems);



