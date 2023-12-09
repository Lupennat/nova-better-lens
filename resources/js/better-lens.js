import LensPage from './pages/Lens';
import LensView from './views/Lens';
import HasManyLensField from './fields/HasManyLensField';
import HasManyThroughLensField from './fields/HasManyThroughLensField';
import BelongsToManyLensField from './fields/BelongsToManyLensField';
import MorphToManyLensField from './fields/MorphToManyLensField';
import LensField from './fields/LensField';

Nova.inertia('Nova.BetterLens', LensPage);

Nova.booting((app, store) => {
    app.component('ResourceBetterLens', LensView);
    app.component('detail-has-many-lens-field', HasManyLensField);
    app.component('detail-has-many-through-lens-field', HasManyThroughLensField);
    app.component('detail-belongs-to-many-lens-field', BelongsToManyLensField);
    app.component('detail-morph-to-many-lens-field', MorphToManyLensField);
    app.component('detail-lens-field', LensField);
});
