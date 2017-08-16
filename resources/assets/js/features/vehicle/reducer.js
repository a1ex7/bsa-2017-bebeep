import * as actions from './actionTypes';

const initialState = {
    vehicles: [],
    vehicle: {},
    create: {
        errors: {}
    }
};

export default function (state = initialState, action) {
    switch(action.type) {
        case actions.VEHICLE_GET_ALL_SUCCESS:
        case actions.VEHICLE_GET_ALL_FAILED: {
            return {
                ...state,
                vehicles: action.vehicles
            };
        }

        case actions.VEHICLE_GET_SUCCESS:
        case actions.VEHICLE_GET_FAILED: {
            return {
                ...state,
                vehicle: action.vehicle
            };
        }

        case actions.VEHICLE_CREATE_SUCCESS: {
            return {
                ...state,
                vehicle: {
                    create: {
                        success: true
                    }
                },
                users: [...state.users, action.user]
            };
        }

        case actions.VEHICLE_CREATE_FAILED: {
            return {
                ...state,
                vehicle: {
                    create: {
                        success: false
                    }
                },
                users: [...state.users, action.user]
            };
        }

        /*case actions.VEHICLE_EDIT: {
            return {
                ...state,
                vehicle: action.vehicle
            };
        }

        case actions.VEHICLE_DELETE: {
            return {
                ...state,
                users: state.users.filter((user) => user.id !== action.id)
            };
        }*/

        default: {
            return state;
        }
    }
};
