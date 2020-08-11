## Version 2.0
The idea is to rewrite some aspects of the application while keeping it simple and basic for anyone at any level to understand what's happening. Here are some areas I'm looking at improving
- Application UI (Unlike the other, this one should come with a user interface - and different from the first)
- Products and variations: I intend to use just one model with a json field to allow for multiple variations. This will obviously disrupt the current logic amd flow of the application.
- Cart: The entire cart needs to be rewritten, except the UI. I think the UI is quite okay for the purpose.
- Sales/Purchases/Dealers/Brands: I want to change the logic of how they all work and communicate with each other
- Reporting: More work need to be done here. The current application only made a subpar attempt at it. Not like I intend to build some badass reporting engine or something. But just a bit better than what the current application provides.
- Notification: Application should use live updates and notifications with Socket.io or Pusher
- Backup: There should be an option to backup entire DB on DropBox or Google Drive.
- Migration from existing system will also be available - and as usual, will be through the use of .xls, .xlsx, or .csv files.

In fact, its the entire app. 

The goal is to improve on how the current application works **while maintaining simplicity for developers at any level**.


## Contribution Guideline
If interested in contributing, please reach me at: <a  href='mailto:chris.idakwo@gmail.com'>chris.idakwo@gmail.com</a>


## License
EFLInventory-V2 is an open-source software licensed under the [GPU v3 License](https://www.google.com.ng/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=0ahUKEwin57Oi5szYAhULBcAKHS0RAQ8QFggnMAA&url=https%3A%2F%2Fwww.gnu.org%2Flicenses%2Fgpl-3.0.en.html).

