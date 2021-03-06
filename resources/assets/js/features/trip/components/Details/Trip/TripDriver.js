import React from 'react';
import { Link } from 'react-router';
import { localize } from 'react-localize-redux';

import TripUserImage from '../TripUserImage';
import { WriteMsgButton } from 'app/components/Buttons';


class TripDriver extends React.Component {

    render() {
        const { driver, translate } = this.props;

        return (
            <section>
                <header className="trip-section-header">
                    <h3 className="h5">
                        { translate('trip_details.driver.header') }
                    </h3>
                </header>

                <div className="d-flex">
                    <TripUserImage
                        user={ driver }
                        type="driver"
                        className="trip-user-image mr-4 mb-0"
                    />

                    <div className="driver-info">
                        <Link to={"/driver/" + driver.id} className="driver-info__link">
                            <strong>{ driver.full_name }</strong>
                        </Link>
                        <span className="trip-text-label driver-age mt-2">
                            { translate('user.age', {age: driver.age}) }
                        </span>

                        <WriteMsgButton userId={driver.id}
                            className="mt-3 w-100"
                        />
                    </div>
                </div>
            </section>
        )
    }
}

export default localize(TripDriver, 'locale');
