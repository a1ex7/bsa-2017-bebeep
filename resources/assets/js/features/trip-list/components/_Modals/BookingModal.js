import React from 'react';
import BookingInfo from '../BookingInfo';
import { getTranslate } from 'react-localize-redux';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';

import Modal from 'app/components/Modal';
import BookingService, {BOOKING_STATUS_DECLINED, BOOKING_STATUS_APPROVED} from 'app/services/BookingService';

import '../../styles/booking-info.scss';

class BookingModal extends React.Component {
    constructor() {
        super();
        this.state = {
            modalIsOpen: false,
        };
    }

    componentWillReceiveProps(newProps) {
        if (this.state.modalIsOpen !== newProps.isOpen) {
            this.setState({
                modalIsOpen: newProps.isOpen
            });
        }
    }

    onActionClick(tripId, bookingId, status) {
        const data = {
            status
        };

        BookingService.updateBookingStatus(tripId, bookingId, data);
        this.setState({
            modalIsOpen: false
        });
    }


    render() {
        const { modalIsOpen } = this.state;
        const { translate, bookings, tripId, count } = this.props;
        const onClosed = this.props.onClosed || (() => {});

        return (
            <div>
                <Modal isOpen={ modalIsOpen } onClosed={() => { this.state.modalIsOpen = false; onClosed(); }}>
                    <div className={ "modal-header booking-back" }><span><strong>{count}</strong> {translate('booking.bookings_in_pending')}</span></div>
                    <div className="modal-body">
                        <ul className="list-unstyled">
                            {bookings.map((booking, i) =>
                                <BookingInfo
                                    key={ i }
                                    booking={ booking }
                                    onApprove={() => this.onActionClick(tripId, booking.id, BOOKING_STATUS_APPROVED)}
                                    onDecline={() => this.onActionClick(tripId, booking.id, BOOKING_STATUS_DECLINED)}
                                />
                            )}
                        </ul>
                    </div>
                    <div className="modal-footer text-right">
                        <button className="btn btn-sm btn-booking" role="button" onClick={(e) => {
                            this.setState({ modalIsOpen: false });
                        }}>{translate('booking.close_button')}</button>
                    </div>
                </Modal>
            </div>
        )
    }
}

export default connect(
    state => ({
        translate: getTranslate(state.locale)
    }),
    dispatch => bindActionCreators({}, dispatch)
)(BookingModal);