import React from 'react';
import {  Step, Stepper, StepButton } from 'material-ui/Stepper';
import RaisedButton from 'material-ui/RaisedButton';
import FlatButton from 'material-ui/FlatButton';
import {Card, CardActions, CardHeader, CardMedia, CardTitle, CardText} from 'material-ui/Card';
import {Link} from 'react-router';

class Welcome extends React.Component {

    state = {
        stepIndex: 0,
    };

    handleNext = () => {
        const {stepIndex} = this.state;
        if (stepIndex < 2) {
            this.setState({ stepIndex: stepIndex + 1 });
        }
    };

    handlePrev = () => {
        const {stepIndex} = this.state;
        if (stepIndex > 0) {
            this.setState({ stepIndex: stepIndex - 1 });
        }
    };

    getStepContent(stepIndex) {
        const links = [
            "/home/system",
            "/home/booking",
            "/home/user"
        ]
        const description = [
            "manage system administrators, which have same privilege with you",
            "manage booking administrators, which can manage resources in booking system",
            "manage user information, and you can add them into black-list or passed their realname authentication"
        ]
        return (
            <div>
                <CardText>{description[stepIndex]}</CardText>
                <Link to={links[stepIndex]}><RaisedButton label="have a try"></RaisedButton></Link>
            </div>
        );
    }

    render() {
        const {stepIndex} = this.state;
        const contentStyle = { margin: '0 16px' };

        return (
            <div style={{ width: '100%', margin: 'auto' }}>
                <Card >
                    <CardHeader
                        title="Welcome to System Administration"
                        subtitle="Learn to use this system"
                        avatar="http://lorempixel.com/100/100/nature/"
                        />
                    <CardText>
                        <Stepper linear={false} activeStep={stepIndex}>
                            <Step>
                                <StepButton onClick={() => this.setState({ stepIndex: 0 }) }>
                                    Manage system administrators
                                </StepButton>
                            </Step>
                            <Step>
                                <StepButton onClick={() => this.setState({ stepIndex: 1 }) }>
                                    Manage booking administrators
                                </StepButton>
                            </Step>
                            <Step>
                                <StepButton onClick={() => this.setState({ stepIndex: 2 }) }>
                                    Manage users
                                </StepButton>
                            </Step>
                        </Stepper>
                        <div style={contentStyle}>
                            {this.getStepContent(stepIndex) }
                        </div>

                    </CardText>
                    <CardActions>
                        <div style={{ marginTop: 12 }}>
                            <FlatButton
                                label="Back"
                                disabled={stepIndex === 0}
                                onTouchTap={this.handlePrev}
                                style={{ marginRight: 12 }}
                                />
                            <RaisedButton
                                label="Next"
                                disabled={stepIndex === 2}
                                primary={true}
                                onTouchTap={this.handleNext}
                                />
                        </div>
                    </CardActions>
                </Card>
            </div>
        );
    }
}

Welcome.contextTypes = {
    onMessage: React.PropTypes.func,
    router: Object
}

export default Welcome;