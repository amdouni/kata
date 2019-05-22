## How did I resolved this problem ? 

When reading the code for the first time, It was a little bit tricky to understand it. So these are differents steps I've followed :

1. Adding simple dockerization to the project. It's optional but in my machine every project is dockerized. 
2. Widely inspired by doctrine ORM, I've replaced Ids by their respective objects directly in the quote entity. 
 This way, I avoid calling other repositories outside of the Quote if not needed. 
 I did'nt modified the Quote constructor signature because the Quote Repository prevents it.
3. The rendering of the Quote should not be within the Quote it self. It can be replaced by any content renderer. This was the fist real refactorization action as it makes the Quote and the QuoteRenderer follow at least one of SOLID principles : Single responsibility.
 From now to on, we can typehint the QuoteRenderer With it's parent to grant a Liskov substitute principle. 
4. The processing of placeholders in templateManager was not flexible as it's based on some placeholders. It's hard to add other ones without adding extra code. 
The task was repetitve and the TemplateManager has multiple responsibilities. That's why I've added a PlaceholderHandler that can handle one or many placeholder. 
5. And finally, I'm not fan of clone in PHP. I've never encoutred real problems with it but I avoid using it. So I've replaced it with a simple hydrator that can be developped further.
In the same time, I've changed some names to better fit with the functions. The unit test was also verified. 
I've tried to respect PSR2 without passing a PSR fixer like php-cs-fixer.

Thanks you !
