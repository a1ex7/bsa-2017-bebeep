import React from 'react';
import { localize } from 'react-localize-redux';

class TripVehicle extends React.Component {

    renderPhoto() {
        const vehicle = this.props.vehicle;

        const COLOR_DEFAULT = '#6dcff7';
        const color = vehicle.color ? vehicle.color : COLOR_DEFAULT;

        if (vehicle.photo === null) {
            return (
                <svg className="vehicle-noimage" viewBox="0 0 39.055 39.054"><g><path fill={ color } d="M38.831,14.26c-0.191-0.233-0.476-0.369-0.775-0.369h-3.801c-0.938-2.474-2.16-4.898-3.549-5.813 c-4.805-3.161-17.55-3.161-22.355,0c-1.39,0.916-2.607,3.343-3.55,5.813H1c-0.302,0-0.586,0.136-0.775,0.369 c-0.19,0.232-0.266,0.539-0.204,0.834l0.563,2.728c0.096,0.465,0.506,0.797,0.979,0.797h1.126 c-1.087,1.254-1.614,2.833-1.621,4.413c-0.007,1.952,0.734,3.716,2.089,4.964c0.015,0.013,0.03,0.022,0.044,0.035v3.817 c0,0.827,0.672,1.5,1.5,1.5h3.506c0.828,0,1.5-0.673,1.5-1.5v-1.534h19.641v1.534c0,0.827,0.672,1.5,1.5,1.5h3.506 c0.826,0,1.5-0.673,1.5-1.5v-3.742c1.438-1.317,2.125-3.129,2.134-4.938c0.006-1.634-0.545-3.271-1.696-4.551h1.201 c0.475,0,0.885-0.332,0.979-0.798l0.564-2.727C39.094,14.799,39.021,14.494,38.831,14.26z M9.998,10.583 c3.83-2.521,15.229-2.521,19.057,0c0.744,0.488,1.701,2.461,2.578,4.877H7.422C8.297,13.045,9.254,11.073,9.998,10.583z M5.512,23.408c0-1.63,1.322-2.95,2.951-2.95c1.631,0,2.951,1.32,2.951,2.95s-1.32,2.951-2.951,2.951 C6.834,26.359,5.512,25.038,5.512,23.408z M30.631,26.359c-1.629,0-2.951-1.321-2.951-2.951s1.322-2.95,2.951-2.95 c1.631,0,2.951,1.32,2.951,2.95S32.26,26.359,30.631,26.359z" /></g></svg>
            );
        }
        return (<img src={ vehicle.photo } alt={ vehicle.brand } />);
    }

    render() {
        const { vehicle, translate } = this.props;

        return (
            <section>
                <header className="trip-section-header">
                    <h3 className="h5">
                        { translate('trip_details.vehicle.header') }
                    </h3>
                </header>

                <div className="d-flex">
                    <figure className="vehicle-image mr-4 mb-0">
                        { this.renderPhoto() }
                    </figure>

                    <div className="vehicle-info">
                        <span className="d-block">
                            <strong>{ vehicle.brand }</strong>
                        </span>
                        <span className="trip-text-label vehicle-color mt-2">
                            { translate('trip_details.vehicle.color', {color: vehicle.color}) }
                        </span>
                    </div>
                </div>
            </section>
        )
    }
}

export default localize(TripVehicle, 'locale');
